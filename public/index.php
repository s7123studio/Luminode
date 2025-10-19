<?php

use Luminode\Core\Router;
use Luminode\Core\Exceptions\ViewNotFoundException;
use Whoops\Handler\PrettyPageHandler;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Formatter\LineFormatter;

// --- 1. 启动框架，获取容器和 Whoops 实例 ---
list($container, $whoops) = require dirname(__DIR__) . '/config/bootstrap.php';

// --- 2. 根据环境注册错误和异常处理器 ---
if (isset($_ENV['APP_ENV']) && $_ENV['APP_ENV'] === 'production') {
    // ====================
    //  生产环境
    // ====================
    ini_set('display_errors', 0);
    error_reporting(E_ALL & ~E_DEPRECATED & ~E_STRICT);

    $logHandler = function (Throwable $e) {
        $log = new Logger('luminode');
        $logFile = dirname(__DIR__) . '/storage/logs/app.log';
        $handler = new StreamHandler($logFile, Logger::ERROR);
        $handler->setFormatter(new LineFormatter(null, null, true, true));
        $log->pushHandler($handler);

        $log->error($e->getMessage(), ['exception' => $e]);

        if (!headers_sent()) {
            http_response_code(500);
            header('Content-Type: text/plain; charset=UTF-8');
        }
        exit('服务器发生了一个内部错误，请稍后再试。');
    };

    // 设置致命错误和未捕获异常的处理器
    set_exception_handler($logHandler);

    // 将PHP的非致命错误也转换为异常，并由我们的处理器接管
    set_error_handler(function ($severity, $message, $file, $line) {
        if (!(error_reporting() & $severity)) {
            return;
        }
        throw new \ErrorException($message, 0, $severity, $file, $line);
    });

} else {
    // ====================
    //  开发环境
    // ====================
    $whoops->register();
}

// --- 3. 运行应用 ---
try {
    session_start();
    \Luminode\Core\ORM\BaseModel::setContainer($container);
    $router = $container->get(Router::class);

    // 定义路由
    $router->get('/', 'HomeController@index');
    $router->get('/error-test', function() {
        throw new ViewNotFoundException("View [a_non_existent_view] not found.");
    });

    $router->run();

} catch (Throwable $e) {
    // 在开发环境中，为 Whoops 添加额外信息
    if (isset($_ENV['APP_ENV']) && $_ENV['APP_ENV'] !== 'production') {
        $handler = $whoops->getHandlers()[0];
        if ($handler instanceof PrettyPageHandler) {
            $router = $container->get(Router::class);
            $handler->addDataTable('Luminode 路由表', $router->getRoutes());

            if ($e instanceof ViewNotFoundException) {
                if (preg_match("/[\\\[(.*?)\\\]]*/", $e->getMessage(), $matches)) {
                    $viewName = $matches[1];
                    $solution = [
                        '错误' => "视图 '{$viewName}' 未找到。",
                        '建议' => "1. 检查 `resources/views/{$viewName}.php` 文件是否存在。\n2. 运行 `php luminode make:view {$viewName}` (待实现) 来创建它。",
                        '命令' => "php luminode make:view {$viewName}"
                    ];
                    $handler->addDataTable('💡 解决方案建议', $solution);
                }
            }
        }
    }
    // 最终将异常交给已注册的处理器 (Whoops 或我们的日志处理器)
    // 注意：在生产环境中，此 throw 会被 set_exception_handler 捕获
    // 在开发环境中，此 throw 会被 Whoops 捕获
    throw $e;
}
