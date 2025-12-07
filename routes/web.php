<?php
/*
 * @Description: 
 * @Author: 7123
 * @version: 
 * @Date: 2025-12-01 13:04:45
 * @LastEditors: 7123
 * @LastEditTime: 2025-12-08 03:57:29
 */

use Luminode\Core\Middleware\Authenticate;
use Luminode\Core\Middleware\CsrfMiddleware;
use Luminode\Core\Middleware\LogRequestMiddleware;
use Luminode\Core\Exceptions\ViewNotFoundException;

/** @var \Luminode\Core\Router $router */

// --- 通用路由 ---
$router->get('/', 'HomeController@index', [LogRequestMiddleware::class]);
$router->get('/test', "TestController@index");

// --- 认证路由 ---
$router->get('/register', 'AuthController@showRegistrationForm');
$router->post('/register', 'AuthController@register', [CsrfMiddleware::class]);
$router->get('/login', 'AuthController@showLoginForm');
$router->post('/login', 'AuthController@login', [CsrfMiddleware::class]);
$router->post('/logout', 'AuthController@logout', [CsrfMiddleware::class]);
$router->get('/profile', 'HomeController@profile', [Authenticate::class]);

// --- 演示路由 ---
$router->get('/hello', function() { 
    return "你好，光枢框架！";
});

$router->get('/user/:id', function($id) { 
    return "你正在查看用户 ID 为：" . e($id) . " 的页面。";
});

$router->get('/error-test', function() { 
    throw new ViewNotFoundException("未找到视图 [a_non_existent_view] 。"); 
});

$router->get('/show-form', 'TestController@showCsrfForm');
$router->post('/handle-form', 'TestController@handleCsrfForm', [CsrfMiddleware::class]);

// --- 分组路由演示 ---
// 访问 /admin/dashboard 即可看到效果
$router->group(['prefix' => '/admin', 'middleware' => [Authenticate::class]], function($router) {
    
    $router->get('/dashboard', function() {
        return "欢迎来到管理员仪表盘！(此前缀 /admin 由分组自动添加，且受 Authenticate 中间件保护)";
    });

    $router->get('/users', function() {
        return "用户管理页面";
    });
});
