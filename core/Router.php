<?php

class Router {
    private $routes = [];
    private $paramRoutes = [];
    
    public function get($path, $callback) {
        if (strpos($path, ':') !== false) {
            $this->addParamRoute('GET', $path, $callback);
        } else {
            $this->routes['GET'][$path] = $callback;
        }
    }
    
    public function post($path, $callback) {
        if (strpos($path, ':') !== false) {
            $this->addParamRoute('POST', $path, $callback);
        } else {
            $this->routes['POST'][$path] = $callback;
        }
    }
    
    private function addParamRoute($method, $path, $callback) {
        $pathParts = explode('/', $path);
        $paramNames = [];
        $regexPath = '';
        
        foreach ($pathParts as $part) {
            if (strpos($part, ':') === 0) {
                $paramName = substr($part, 1);
                $paramNames[] = $paramName;
                $regexPath .= '/([^/]+)';
            } else {
                $regexPath .= '/' . $part;
            }
        }
        
        $this->paramRoutes[$method][] = [
            'regex' => '#^' . $regexPath . '$#',
            'callback' => $callback,
            'paramNames' => $paramNames
        ];
    }
    
    public function run() {
        $method = $_SERVER['REQUEST_METHOD'];
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        
        // 检查静态路由
        if (isset($this->routes[$method][$path])) {
            return call_user_func($this->routes[$method][$path]);
        }
        
        // 检查动态路由
        if (isset($this->paramRoutes[$method])) {
            foreach ($this->paramRoutes[$method] as $route) {
                if (preg_match($route['regex'], $path, $matches)) {
                    array_shift($matches);
                    $params = array_combine($route['paramNames'], $matches);
                    return call_user_func_array($route['callback'], $params);
                }
            }
        }
        
        http_response_code(404);
        include '../app/views/404.php';
    }
}