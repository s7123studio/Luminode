<?php

// 返回一个包含数据库连接配置的数组
return [
    // 数据库驱动类型，这里使用的是 MySQL
    'driver'    => 'mysql',
    // 数据库服务器地址，这里使用的是本地主机
    'host'      => 'localhost',
    // 数据库名称
    'dbname'    => 'dbname',
    // 数据库用户名
    'username'  => 'dbusername',
    // 数据库密码
    'password'  => 'dbpassword',
    // 数据库字符集，这里使用的是 utf8mb4，支持存储更多的字符，包括表情符号
    'charset'   => 'utf8mb4',
    // 数据库排序规则，这里使用的是 utf8mb4_unicode_ci，不区分大小写
    'collation' => 'utf8mb4_unicode_ci',
    // 数据库表前缀，这里为空，表示不使用前缀
    'prefix'    => '',
    
    // PDO 连接选项
    'options' => [
        // 设置 PDO 错误模式为异常模式，这样当发生数据库错误时会抛出异常
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        // 设置默认的提取模式为关联数组
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        // 禁用 PDO 的预处理语句模拟，使用原生预处理语句
        PDO::ATTR_EMULATE_PREPARES   => false
    ]
];