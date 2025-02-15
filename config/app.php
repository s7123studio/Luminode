<?php

// 返回一个配置数组，包含缓存、上传和分页的配置信息
return [
    'cache' => [
        'driver' => 'auto', // apcu/file
        'ttl' => 3600
    ],
    'uploads' => [
        'path' => 'public/uploads',
        'max_size' => 5 * 1024 * 1024, // 5MB
        'allowed_types' => ['image/jpeg', 'image/png']
    ],
    'pagination' => [
        'per_page' => 10,
        'max_links' => 5
    ]
];