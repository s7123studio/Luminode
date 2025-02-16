<?php

// 返回一个配置数组，用于设置邮件发送的相关参数
return [
    // 默认的邮件发送方式，从环境变量中获取MAIL_MAILER的值，如果没有设置则默认为'smtp'
    'default' => env('MAIL_MAILER', 'smtp'),
    
    // 定义支持的邮件发送方式及其配置
    'mailers' => [
        // SMTP邮件发送方式的配置
        'smtp' => [
            // 指定使用SMTP作为传输方式
            'transport' => 'smtp',
            // SMTP服务器地址，从环境变量中获取MAIL_HOST的值，如果没有设置则默认为'smtp.qq.org'
            'host' => env('MAIL_HOST', 'smtp.qq.org'),
            // SMTP服务器端口，从环境变量中获取MAIL_PORT的值，如果没有设置则默认为587
            'port' => env('MAIL_PORT', 587),
            // 加密方式，从环境变量中获取MAIL_ENCRYPTION的值，如果没有设置则默认为'tls'
            'encryption' => env('MAIL_ENCRYPTION', 'tls'),
            // SMTP用户名，从环境变量中获取MAIL_USERNAME的值
            'username' => env('MAIL_USERNAME'),
            // SMTP密码，从环境变量中获取MAIL_PASSWORD的值
            'password' => env('MAIL_PASSWORD'),
            // 连接超时时间，默认为null，表示使用系统默认值
            'timeout' => null,
        ],
        
        // 日志邮件发送方式的配置
        'log' => [
            // 指定使用日志作为传输方式
            'transport' => 'log',
            // 日志通道，从环境变量中获取MAIL_LOG_CHANNEL的值
            'channel' => env('MAIL_LOG_CHANNEL'),
        ],
    ],

    // 发件人配置
    'from' => [
        // 发件人邮箱地址，从环境变量中获取MAIL_FROM_ADDRESS的值，如果没有设置则默认为'hello@example.com'
        'address' => env('MAIL_FROM_ADDRESS', 'hello@example.com'),
        // 发件人名称，从环境变量中获取MAIL_FROM_NAME的值，如果没有设置则默认为'Luminode'
        'name' => env('MAIL_FROM_NAME', 'Luminode'),
    ],
];