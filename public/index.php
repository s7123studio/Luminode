<?php
// 定义应用根目录和基础URL
define('APP_ROOT', dirname(__DIR__));
define('BASE_URL', '');

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

// 演示功能路由
$router->get('/demo', function() {
    $controller = new DemoController();
    $controller->showView();
});

$router->get('/demo/db', function() {
    $controller = new DemoController();
    $controller->showDbDemo();
});

$router->get('/demo/form', function() {
    $controller = new DemoController();
    $controller->showFormDemo();
});

$router->post('/demo/form', function() {
    $controller = new DemoController();
    $controller->showFormDemo();
});

$router->get('/demo/template', function() {
    $controller = new DemoController();
    $controller->showTemplateDemo();
});

// 运行路由
$router->run();