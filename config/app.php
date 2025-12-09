<?php

$env = env('APP_ENV', 'production');

return [
    'name' => env('APP_NAME', 'Luminode'),
    'env' => $env,
    // 智能默认值：如果是开发环境，默认开启调试
    'debug' => (bool) env('APP_DEBUG', $env === 'development'),
    'url' => env('APP_URL', 'http://localhost'),
    'timezone' => 'Asia/Shanghai',
    'locale' => 'zh-CN',
    // URL 后缀，例如 '.html'。留空则不启用。
    'url_suffix' => env('URL_SUFFIX', ''),
];
