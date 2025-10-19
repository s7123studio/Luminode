<?php

use DI\ContainerBuilder;
use Luminode\Core\Database;
use Luminode\Core\Template;
use Whoops\Run as WhoopsRun;
use Whoops\Handler\PrettyPageHandler;

// --- 1. 定义常量和加载自动加载器 ---
if (!defined('APP_ROOT')) {
    define('APP_ROOT', dirname(__DIR__));
}
require APP_ROOT . '/vendor/autoload.php';

// --- 2. 加载环境变量 ---
$dotenv = Dotenv\Dotenv::createImmutable(APP_ROOT);
$dotenv->load();

// --- 3. 创建和配置 DI 容器 ---
$containerBuilder = new ContainerBuilder();
$containerBuilder->addDefinitions([
    Database::class => function () {
        return new Database();
    },
    Template::class => function () {
        return new Template(APP_ROOT . '/resources/views');
    },
]);
$container = $containerBuilder->build();

// --- 4. 创建 Whoops 实例 (但是不注册) ---
$whoops = new WhoopsRun;
$prettyPageHandler = new PrettyPageHandler;
$prettyPageHandler->setEditor('vscode');
$whoops->pushHandler($prettyPageHandler);

// --- 5. 返回容器和 Whoops 实例 ---
return [$container, $whoops];
