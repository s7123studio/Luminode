<?php
// 引入核心路由类文件
require '../core/Router.php';
// 引入核心数据库类文件
require '../core/Database.php';
// 引入核心认证类文件
require '../core/Auth.php';
// 自动加载Controller
require '../core/Controller.php';
// 启动会话
session_start();

// 实例化 Router 类
$router = new Router();

// 路由配置
$router->get('/', function() {
    // 使用HomeController 调用其 index 方法
    
    (new HomeController())->index();
});

$router->get('/admin', function() {
    // 使用AdminController 调用其 index 方法
    (new AdminController())->index();
});

$router->post('/login', function() {
    include '../app/controllers/admin_login.php';
});

$router->post('/update_settings', function() {
    include '../app/controllers/update_settings.php';
});

$router->get('/gpu', function() {
    (new GpuController())->index();
});


$router->get('/gpu/rank', function() {
    (new GpuRankController())->index();
});

$router->post('/gpu/submit_score', function() {
    include '../app/controllers/submit_score.php';
});

$router->get('/2024YR4', function() {
    (new X2024YR4())->index();
});

$router->get('/guide', function() {
    // 使用HomeController 调用其 guide 方法
    (new HomeController())->guide();
});

// 定义 '/post/{id}' 路径的 GET 请求处理函数
$router->get('/post/{id}', function() {
    // 从 GET 参数中获取 id 并转换为整数
    $id = (int) $_GET['id'];
    // 使用HomeController 类并调用其 showPost 方法，传入 id 参数
    (new HomeController())->showPost($id);
});
// 更多路由...

// 运行路由，匹配当前请求并执行相应的处理函数
$router->run();