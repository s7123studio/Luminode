<?php
/*
 * @Author: 7123
 * @Date: 2025-03-09 22:43:28
 * @LastEditors: 7123
 * @LastEditTime: 2025-12-10 02:57:57
 */

namespace Luminode\Core;

use DI\Container;
use Exception;
use Closure;
use Luminode\Core\Exceptions\RouteNotFoundException;
use Luminode\Core\Middleware\MiddlewareInterface;
use Luminode\Core\Response;

class Router
{
    private array $routes = [];
    private array $paramRoutes = [];
    private Container $container;

    private string $groupPrefix = '';
    private string $groupNamespace = '';
    private array $groupMiddleware = [];
    private array $globalMiddleware = [];

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function addMiddleware(string $middleware): void
    {
        $this->globalMiddleware[] = $middleware;
    }

    public function group(array $attributes, Closure $callback): void
    {
        // 暂存当前的分组状态
        $previousPrefix = $this->groupPrefix;
        $previousMiddleware = $this->groupMiddleware;
        $previousNamespace = $this->groupNamespace;

        // 更新分组状态
        if (isset($attributes['prefix'])) {
            $this->groupPrefix .= '/' . trim($attributes['prefix'], '/');
        }
        
        if (isset($attributes['namespace'])) {
            $this->groupNamespace .= '\\' . trim($attributes['namespace'], '\\');
        }
        
        if (isset($attributes['middleware'])) {
            $newMiddleware = is_array($attributes['middleware']) ? $attributes['middleware'] : [$attributes['middleware']];
            $this->groupMiddleware = array_merge($this->groupMiddleware, $newMiddleware);
        }

        // 执行闭包，注册路由
        $callback($this);

        // 恢复之前的分组状态
        $this->groupPrefix = $previousPrefix;
        $this->groupMiddleware = $previousMiddleware;
        $this->groupNamespace = $previousNamespace;
    }

    public function get(string $path, $callback, array $middleware = []): void
    {
        $this->addRoute('GET', $path, $callback, $middleware);
    }

    public function post(string $path, $callback, array $middleware = []): void
    {
        $this->addRoute('POST', $path, $callback, $middleware);
    }

    public function put(string $path, $callback, array $middleware = []): void
    {
        $this->addRoute('PUT', $path, $callback, $middleware);
    }

    public function patch(string $path, $callback, array $middleware = []): void
    {
        $this->addRoute('PATCH', $path, $callback, $middleware);
    }

    public function delete(string $path, $callback, array $middleware = []): void
    {
        $this->addRoute('DELETE', $path, $callback, $middleware);
    }

    private function addRoute(string $method, string $path, $callback, array $middleware = []): void
    {
        // 应用分组前缀
        $path = $this->groupPrefix . '/' . trim($path, '/');
        // 确保根路径不是空的
        if ($path !== '/') {
             $path = rtrim($path, '/');
        }
        if (empty($path)) {
            $path = '/';
        }

        // 应用分组中间件
        $finalMiddleware = array_merge($this->groupMiddleware, $middleware);

        if (strpos($path, ':') !== false) {
            $this->addParamRoute($method, $path, $callback, $finalMiddleware);
        } else {
            $this->routes[$method][$path] = ['callback' => $callback, 'middleware' => $finalMiddleware];
        }
    }

    private function addParamRoute(string $method, string $path, $callback, array $middleware = []): void
    {
        $pathParts = explode('/', $path);
        $paramNames = [];
        $regexPath = '';

        foreach ($pathParts as $part) {
            if (empty($part)) continue;

            if (strpos($part, ':') === 0) {
                $paramName = substr($part, 1);
                $paramNames[] = $paramName;
                $regexPath .= '/([^/]+)';
            } else {
                $regexPath .= '/' . preg_quote($part, '#');
            }
        }

        $regex = '#^' . $regexPath . '$#';
        $this->paramRoutes[$method][] = [
            'path' => $path,
            'regex' => $regex,
            'callback' => $callback,
            'paramNames' => $paramNames,
            'middleware' => $middleware
        ];
    }

    public function run(): Response
    {
        $method = $_SERVER['REQUEST_METHOD'];

        // Handle method spoofing for PUT, PATCH, DELETE from forms
        if ($method === 'POST' && isset($_POST['_method'])) {
            $spoofedMethod = strtoupper($_POST['_method']);
            if (in_array($spoofedMethod, ['PUT', 'PATCH', 'DELETE'])) {
                $method = $spoofedMethod;
            }
        }

        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?: '/';

        // URL 后缀处理 (URL Suffix)
        if (function_exists('config')) {
            $suffix = config('app.url_suffix');
            if ($suffix && $path !== '/' && str_ends_with($path, $suffix)) {
                $path = substr($path, 0, -strlen($suffix));
                if (empty($path)) {
                    $path = '/';
                }
            }
        }

        $routeData = $this->findRoute($method, $path);

        if ($routeData === null) {
            throw new RouteNotFoundException("未找到路由：{$method} {$path}");
        }

        $finalCallback = function (Container $container) use ($routeData) {
            return $this->executeCallback($routeData['callback'], $routeData['params']);
        };

        // 合并全局中间件和路由特定的中间件
        // 全局中间件应该先执行（所以在洋葱模型中应该放在数组后面？不，洋葱模型是 reverse 的）
        // 假设我们希望全局中间件包裹路由中间件： Global(Route(App))
        // 那么 pipeline 构造顺序应该是：Route -> Global
        // resolveMiddlewareStack 是 reverse 遍历的。
        // 如果我们传入 [Global, Route]，reverse 后是 Route, Global。
        // pipeline = Global(Route(App))。
        // 所以顺序应该是 array_merge($globalMiddleware, $routeMiddleware)
        
        $allMiddleware = array_merge($this->globalMiddleware, $routeData['middleware']);

        $pipeline = $this->resolveMiddlewareStack($allMiddleware, $finalCallback);

        return $pipeline($this->container);
    }

    private function findRoute(string $method, string $path): ?array
    {
        if (isset($this->routes[$method][$path])) {
            $route = $this->routes[$method][$path];
            return [
                'callback' => $route['callback'],
                'params' => [],
                'middleware' => $route['middleware']
            ];
        }

        if (isset($this->paramRoutes[$method])) {
            foreach ($this->paramRoutes[$method] as $route) {
                if (preg_match($route['regex'], $path, $matches)) {
                    array_shift($matches);
                    $params = array_combine($route['paramNames'], $matches);
                    return [
                        'callback' => $route['callback'],
                        'params' => $params,
                        'middleware' => $route['middleware']
                    ];
                }
            }
        }

        return null;
    }

    private function resolveMiddlewareStack(array $middlewareClasses, Closure $finalCallback): Closure
    {
        $pipeline = $finalCallback;

        foreach (array_reverse($middlewareClasses) as $middlewareClass) {
            $pipeline = function (Container $container) use ($middlewareClass, $pipeline) {
                /** @var MiddlewareInterface $middlewareInstance */
                $middlewareInstance = $container->get($middlewareClass);
                if (!$middlewareInstance instanceof MiddlewareInterface) {
                    throw new Exception("Class {$middlewareClass} must implement MiddlewareInterface.");
                }
                return $middlewareInstance->handle($container, $pipeline);
            };
        }

        return $pipeline;
    }

    private function executeCallback($callback, array $params = []): Response
    {
        $result = null;

        if (is_string($callback) && strpos($callback, '@') !== false) {
            list($controllerName, $methodName) = explode('@', $callback, 2);
            
            // 命名空间处理逻辑
            if (str_starts_with($controllerName, '\\')) {
                // 如果是 FQCN (以 \ 开头)，直接使用，不拼接任何前缀
                $controllerClass = $controllerName;
            } else {
                // 拼接基础命名空间 + 分组命名空间 + 控制器名
                // 默认基础命名空间是 App\Controllers
                // 注意：groupNamespace 已经包含了开头的 \ (如果有设置)
                $namespace = 'App\\Controllers' . $this->groupNamespace;
                $controllerClass = $namespace . '\\' . $controllerName;
            }

            if (!class_exists($controllerClass)) {
                throw new Exception("未找到控制器类：{$controllerClass}");
            }

            $controllerInstance = $this->container->get($controllerClass);

            if (!method_exists($controllerInstance, $methodName)) {
                throw new Exception("控制器 {$controllerClass} 中未找到方法：{$methodName}");
            }

            $result = call_user_func_array([$controllerInstance, $methodName], $params);

        } elseif (is_callable($callback)) {

            $result = call_user_func_array($callback, $params);
        } else {
            throw new Exception("无效的路由回调函数。");
        }

        // Auto-wrap response
        if ($result instanceof Response) {
            return $result;
        }

        if (is_string($result) || is_numeric($result)) {
            return new Response((string)$result);
        }

        if (is_array($result) || $result instanceof \JsonSerializable) {
            return new Response(json_encode($result), 200, ['Content-Type' => 'application/json']);
        }
        
        if (is_null($result)) {
             return new Response('');
        }

        throw new Exception("路由回调返回了无效类型。期望字符串、数组或 Response 对象。");
    }

    public function getRoutes(): array
    {
        $allRoutes = [];

        foreach ($this->routes as $method => $routes) {
            foreach ($routes as $path => $routeData) {
                $allRoutes[] = [
                    'method' => $method,
                    'path' => $path,
                    'action' => $this->formatCallback($routeData['callback']),
                    'middleware' => implode(', ', $routeData['middleware']),
                ];
            }
        }

        foreach ($this->paramRoutes as $method => $routes) {
            foreach ($routes as $route) {
                $allRoutes[] = [
                    'method' => $method,
                    'path' => $route['path'],
                    'action' => $this->formatCallback($route['callback']),
                    'middleware' => implode(', ', $route['middleware']),
                ];
            }
        }
        return $allRoutes;
    }

    private function formatCallback($callback): string
    {
        if (is_string($callback)) {
            return $callback;
        }
        if ($callback instanceof Closure) {
            return 'Closure';
        }
        if (is_array($callback) && isset($callback[0]) && is_object($callback[0])) {
             return get_class($callback[0]) . '@' . $callback[1];
        }
        return 'Unknown';
    }
}
