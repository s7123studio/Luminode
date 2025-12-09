<?php

namespace Luminode\Core;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;

/**
 * 极简 HTTP 客户端门面
 * 
 * @method static Response get(string $url, array $options = [])
 * @method static Response post(string $url, array $options = [])
 * @method static Response put(string $url, array $options = [])
 * @method static Response delete(string $url, array $options = [])
 * @method static Response patch(string $url, array $options = [])
 */
class Http
{
    private static ?Client $client = null;

    public static function instance(): Client
    {
        if (self::$client === null) {
            self::$client = new Client([
                'timeout'  => 5.0,
                'http_errors' => false, // 不抛出异常，让用户处理状态码
            ]);
        }
        return self::$client;
    }

    public static function __callStatic($method, $args)
    {
        return self::instance()->request($method, ...$args);
    }

    public static function json(string $url, array $data = [], string $method = 'POST')
    {
        return self::instance()->request($method, $url, ['json' => $data]);
    }
}
