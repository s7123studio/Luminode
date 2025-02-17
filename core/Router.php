<?php

// 定义一个路由器类，用于处理HTTP请求的路由
class Router {
    // 用于存储路由信息的私有属性，初始为空数组
    private $routes = [];
    
    // 定义一个方法用于注册GET请求的路由
    // $path: 路由的路径
    // $callback: 当匹配到该路由时执行的回调函数
    public function get($path, $callback) {
        // 将路由路径和回调函数存储到routes数组的GET键下
        $this->routes['GET'][$path] = $callback;
    }
    
    // 定义一个方法用于注册POST请求的路由
    // $path: 路由的路径
    // $callback: 当匹配到该路由时执行的回调函数
    public function post($path, $callback) {
        // 将路由路径和回调函数存储到routes数组的POST键下
        $this->routes['POST'][$path] = $callback;
    }
    
    // 定义一个方法用于运行路由器，处理实际的HTTP请求
    public function run() {
        // 获取当前请求的HTTP方法（如GET, POST等）
        $method = $_SERVER['REQUEST_METHOD'];
        // 获取当前请求的URI路径
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        
        // 遍历当前HTTP方法对应的所有路由
        foreach ($this->routes[$method] as $route => $callback) {
            // 如果当前请求的路径与某个路由路径匹配
            if ($route === $path) {
                // 执行对应的回调函数并返回结果
                return call_user_func($callback);
            }
        }
        
        // 如果没有找到匹配的路由，返回404状态码
        http_response_code(404);
        // 输出"Page not found"表示页面未找到
        echo "Page not found";
    }
}