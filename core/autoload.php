<?php

spl_autoload_register(function ($className) {
    // 定义类文件查找目录
    $directories = [
        APP_ROOT . '/core/',
        APP_ROOT . '/app/controllers/',
        APP_ROOT . '/app/models/',
    ];

    // 转换命名空间分隔符为目录分隔符
    $className = str_replace('\\', DIRECTORY_SEPARATOR, $className);
    
    // 在定义的目录中查找类文件
    foreach ($directories as $directory) {
        $file = $directory . $className . '.php';
        
        if (file_exists($file)) {
            require $file;
            return;
        }
    }
    
    // 类文件未找到
    throw new RuntimeException("类 {$className} 未找到");
});

// 加载核心文件
require APP_ROOT . '/core/Router.php';
require APP_ROOT . '/core/Database.php';
require APP_ROOT . '/core/Controller.php';
require APP_ROOT . '/core/Auth.php';
