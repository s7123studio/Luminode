<?php
// 定义应用根目录
define('APP_ROOT', dirname(__DIR__));

// 注册自动加载
require APP_ROOT . '/core/autoload.php';

// 启动会话
session_start();

// 实例化 Router 类
$router = new Router();

// 路由配置
$router->get('/', function() {
    $controller = new HomeController();
    $controller->index();
});

$router->get('/admin', function() {
    $controller = new AdminController();
    $controller->index();
});

$router->post('/login', function() {
    $controller = new AdminLoginController();
    $controller->login();
});

$router->post('/update_settings', function() {
    $controller = new UpdateSettingsController();
    $controller->update();
});

$router->get('/guide', function() {
    $controller = new HomeController();
    $controller->guide();
});

$router->get('/post/{id}', function($id) {
    $controller = new HomeController();
    $controller->showPost($id);
});

// 运行路由
$router->run();