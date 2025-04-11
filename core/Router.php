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
        error_log("注册动态路由: method=$method, path=$path, regex=$regex");
        
        $this->paramRoutes[$method][] = [
            'regex' => $regex,
            'callback' => $callback,
            'paramNames' => $paramNames
        ];
    }
    
    public function run() {
        $method = $_SERVER['REQUEST_METHOD'];
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        error_log("尝试匹配路由: method=$method, path=$path");

        if (isset($demoRoutes[$path])) {
            error_log("匹配演示路由: $method $path");
            return call_user_func($demoRoutes[$path]);
        }
        
        // 检查静态路由
        if (isset($this->routes[$method][$path])) {
            error_log("匹配静态路由: $method $path");
            return call_user_func($this->routes[$method][$path]);
        }
        
        // 检查动态路由
        if (isset($this->paramRoutes[$method])) {
            foreach ($this->paramRoutes[$method] as $route) {
                error_log("尝试匹配动态路由: ".$route['regex']);
                if (preg_match($route['regex'], $path, $matches)) {
                    error_log("匹配成功，参数: ".json_encode($matches));
                    array_shift($matches);
                    $params = array_combine($route['paramNames'], $matches);
                    return call_user_func_array($route['callback'], $params);
                }
            }
        }
        
        error_log("没有找到匹配的路由");
        
        http_response_code(404);
        include '../app/views/404.php';
    }
}