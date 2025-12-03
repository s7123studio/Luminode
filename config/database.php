<?php
/*
 * @Author: 7123
 * @Date: 2025-03-09 22:43:28
 * @LastEditors: 7123
 * @LastEditTime: 2025-12-03 19:26:06
 */

// config/database.php

// 数据库配置现在从环境变量中读取
// 环境变量定义在项目根目录的 .env 文件中

$config = [
    'driver'    => 'mysql',
    'host'      => $_ENV['DB_HOST'] ?? '127.0.0.1',
    'dbname'    => $_ENV['DB_DATABASE'] ?? 'luminode_db',
    'username'  => $_ENV['DB_USERNAME'] ?? 'root',
    'password'  => $_ENV['DB_PASSWORD'] ?? '',
    'charset'   => 'utf8mb4',
    'collation' => 'utf8mb4_unicode_ci',
    'options' => [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false
    ]
];
