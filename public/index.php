<?php

// 引入核心路由类文件
require '../core/Router.php';
// 引入核心数据库类文件
require '../core/Database.php';
// 引入核心认证类文件
require '../core/Auth.php';

// 启动会话
session_start();

// 创建路由对象
$router = new Router();

// 路由配置

// 定义根路径 '/' 的 GET 请求处理函数
$router->get('/', function() {
    // 实例化 HomeController 并调用其 index 方法
    (new HomeController())->index();
});

// 定义 '/post/{id}' 路径的 GET 请求处理函数
$router->get('/post/{id}', function() {
    // 从 GET 参数中获取 id 并转换为整数
    $id = (int) $_GET['id'];
    // 实例化 HomeController 并调用其 showPost 方法，传入 id 参数
    (new HomeController())->showPost($id);
});

// 更多路由...

// 运行路由，匹配当前请求并执行相应的处理函数
$router->run();