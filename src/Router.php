<?php

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

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function get(string $path, $callback, array $middleware = []): void
    {
        if (strpos($path, ':') !== false) {
            $this->addParamRoute('GET', $path, $callback, $middleware);
        } else {
            $this->routes['GET'][$path] = ['callback' => $callback, 'middleware' => $middleware];
        }
    }

    public function post(string $path, $callback, array $middleware = []): void
    {
        if (strpos($path, ':') !== false) {
            $this->addParamRoute('POST', $path, $callback, $middleware);
        } else {
            $this->routes['POST'][$path] = ['callback' => $callback, 'middleware' => $middleware];
        }
    }

    public function put(string $path, $callback, array $middleware = []): void
    {
        if (strpos($path, ':') !== false) {
            $this->addParamRoute('PUT', $path, $callback, $middleware);
        } else {
            $this->routes['PUT'][$path] = ['callback' => $callback, 'middleware' => $middleware];
        }
    }

    public function patch(string $path, $callback, array $middleware = []): void
    {
        if (strpos($path, ':') !== false) {
            $this->addParamRoute('PATCH', $path, $callback, $middleware);
        } else {
            $this->routes['PATCH'][$path] = ['callback' => $callback, 'middleware' => $middleware];
        }
    }

    public function delete(string $path, $callback, array $middleware = []): void
    {
        if (strpos($path, ':') !== false) {
            $this->addParamRoute('DELETE', $path, $callback, $middleware);
        } else {
            $this->routes['DELETE'][$path] = ['callback' => $callback, 'middleware' => $middleware];
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

        $routeData = $this->findRoute($method, $path);

        if ($routeData === null) {
            throw new RouteNotFoundException("No route found for {$method} {$path}");
        }

        $finalCallback = function (Container $container) use ($routeData) {
            return $this->executeCallback($routeData['callback'], $routeData['params']);
        };

        $pipeline = $this->resolveMiddlewareStack($routeData['middleware'], $finalCallback);

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
        if (is_string($callback) && strpos($callback, '@') !== false) {
            list($controllerName, $methodName) = explode('@', $callback, 2);
            $controllerClass = "App\\Controllers\\" . $controllerName;

            if (!class_exists($controllerClass)) {
                throw new Exception("Controller class {$controllerClass} not found.");
            }

            $controllerInstance = $this->container->get($controllerClass);

            if (!method_exists($controllerInstance, $methodName)) {
                throw new Exception("Method {$methodName} not found in controller {$controllerClass}.");
            }

            return call_user_func_array([$controllerInstance, $methodName], $params);

        } elseif (is_callable($callback)) {
            return call_user_func_array($callback, $params);
        } else {
            throw new Exception("Invalid route callback.");
        }
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
