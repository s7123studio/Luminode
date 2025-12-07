<?php
/*
 * @Author: 7123
 * @Date: 2025-03-09 22:43:28
 * @LastEditors: 7123
 * @LastEditTime: 2025-12-08 03:46:30
 */

use Luminode\Core\Router;
use Luminode\Core\Response;
use Luminode\Core\Exceptions\CsrfTokenMismatchException;
use Luminode\Core\Exceptions\RouteNotFoundException;
use Luminode\Core\Exceptions\ViewNotFoundException;
use Luminode\Core\Middleware\Authenticate;
use Luminode\Core\Middleware\CsrfMiddleware;
use Luminode\Core\Middleware\LogRequestMiddleware;
use Whoops\Handler\PrettyPageHandler;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Formatter\LineFormatter;

// --- 1. 启动框架，获取容器和 Whoops 实例 ---
list($container, $whoops) = require dirname(__DIR__) . '/config/bootstrap.php';

// --- 2. 错误处理初始化 ---
if (config('app.env') === 'production') {
    ini_set('display_errors', 0);
    error_reporting(E_ALL & ~E_DEPRECATED & ~E_STRICT);
} else {
    $whoops->register();
}

// --- 3. 运行应用 ---
try {
    session_start();
    
    // 初始化 ORM
    \Luminode\Core\ORM\BaseModel::setContainer($container);
    
    // 获取 Router
    $router = $container->get(Router::class);

    // 加载全局辅助函数 (如果 Composer 未自动加载)
    if (file_exists(dirname(__DIR__) . '/src/helpers.php')) {
        require_once dirname(__DIR__) . '/src/helpers.php';
    }

    // 加载路由
    require dirname(__DIR__) . '/routes/web.php';

    // 运行路由
    $response = $router->run();
    
    // 发送响应
    if ($response instanceof Response) {
        $response->send();
    }

} catch (Throwable $e) {
    // 如果是开发环境且 Whoops 已注册，Whoops 会捕获大部分未处理异常。
    // 但如果想自定义特定异常(如404)的显示，可以使用 Handler。
    
    $handler = new \App\Exceptions\Handler();
    
    try {
        $response = $handler->render($e);
        $response->send();
    } catch (Throwable $originalException) {
        // 如果 Handler 再次抛出异常 (例如在 Debug 模式下抛出以触发 Whoops)
        throw $originalException;
    }
}