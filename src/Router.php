<?php

namespace Luminode\Core;

use DI\Container;
use Exception;
use Closure;

class Router
{
    private array $routes = [];
    private array $paramRoutes = [];
    private Container $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function get(string $path, $callback): void
    {
        if (strpos($path, ':') !== false) {
            $this->addParamRoute('GET', $path, $callback);
        } else {
            $this->routes['GET'][$path] = $callback;
        }
    }

    public function post(string $path, $callback): void
    {
        if (strpos($path, ':') !== false) {
            $this->addParamRoute('POST', $path, $callback);
        } else {
            $this->routes['POST'][$path] = $callback;
        }
    }

    private function addParamRoute(string $method, string $path, $callback): void
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
            'path' => $path, // Store the original path for listing
            'regex' => $regex,
            'callback' => $callback,
            'paramNames' => $paramNames
        ];
    }

    public function run(): void
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?: '/';

        $routeData = $this->findRoute($method, $path);

        if ($routeData === null) {
            http_response_code(404);
            header('Content-Type: text/plain');
            exit('404 Not Found');
        }

        $this->executeCallback($routeData['callback'], $routeData['params']);
    }

    private function findRoute(string $method, string $path): ?array
    {
        if (isset($this->routes[$method][$path])) {
            return ['callback' => $this->routes[$method][$path], 'params' => []];
        }

        if (isset($this->paramRoutes[$method])) {
            foreach ($this->paramRoutes[$method] as $route) {
                if (preg_match($route['regex'], $path, $matches)) {
                    array_shift($matches);
                    $params = array_combine($route['paramNames'], $matches);
                    return ['callback' => $route['callback'], 'params' => $params];
                }
            }
        }

        return null;
    }

    private function executeCallback($callback, array $params = []): void
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

            call_user_func_array([$controllerInstance, $methodName], $params);

        } elseif (is_callable($callback)) {
            call_user_func_array($callback, $params);
        } else {
            throw new Exception("Invalid route callback.");
        }
    }

    public function getRoutes(): array
    {
        $allRoutes = [];

        foreach ($this->routes as $method => $routes) {
            foreach ($routes as $path => $callback) {
                $allRoutes[] = [
                    'method' => $method,
                    'path' => $path,
                    'action' => $this->formatCallback($callback),
                ];
            }
        }

        foreach ($this->paramRoutes as $method => $routes) {
            foreach ($routes as $route) {
                $allRoutes[] = [
                    'method' => $method,
                    'path' => $route['path'],
                    'action' => $this->formatCallback($route['callback']),
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
