<?php
/*
 * @Author: 7123
 * @Date: 2025-12-01 13:01:37
 * @LastEditors: 7123
 * @LastEditTime: 2025-12-03 19:21:12
 */

use Luminode\Core\Response;
use Luminode\Core\Template;

if (!function_exists('app')) {
    /**
     * 获取全局容器实例或从容器中解析服务
     *
     * @param string|null $abstract
     * @return mixed|\DI\Container
     */
    function app($abstract = null)
    {
        global $container;
        if ($abstract === null) {
            return $container;
        }
        return $container->get($abstract);
    }
}

if (!function_exists('view')) {
    /**
     * 渲染视图
     *
     * @param string $view
     * @param array $data
     * @return string
     */
    function view(string $view, array $data = []): string
    {
        return app(Template::class)->render($view, $data);
    }
}

if (!function_exists('response')) {
    /**
     * 创建一个新的响应
     *
     * @param string $content
     * @param int $status
     * @param array $headers
     * @return Response
     */
    function response($content = '', int $status = 200, array $headers = []): Response
    {
        return new Response($content, $status, $headers);
    }
}

if (!function_exists('json')) {
    /**
     * 创建一个新的 JSON 响应
     *
     * @param mixed $data
     * @param int $status
     * @param array $headers
     * @return Response
     */
    function json($data, int $status = 200, array $headers = []): Response
    {
        $headers['Content-Type'] = 'application/json';
        return new Response(json_encode($data), $status, $headers);
    }
}

if (!function_exists('redirect')) {
    /**
     * 创建重定向响应
     *
     * @param string $url
     * @param int $status
     * @return Response
     */
    function redirect(string $url, int $status = 302): Response
    {
        return new Response('', $status, ['Location' => $url]);
    }
}

if (!function_exists('env')) {
    /**
     * 获取环境变量的值
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    function env(string $key, $default = null)
    {
        return $_ENV[$key] ?? $default;
    }
}

if (!function_exists('config')) {
    /**
     * 获取配置值
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    function config($key, $default = null)
    {
        try {
            return app(\Luminode\Core\Config::class)->get($key, $default);
        } catch (\Exception $e) {
            return $default;
        }
    }
}

if (!function_exists('database_path')) {
    /**
     * 获取 database 目录的路径
     *
     * @param string $path
     * @return string
     */
    function database_path($path = '')
    {
        return APP_ROOT . '/database' . ($path ? DIRECTORY_SEPARATOR . $path : '');
    }
}

if (!function_exists('e')) {
    /**
     * 对字符串进行 HTML 转义 (htmlspecialchars 的简写)
     *
     * @param string $value
     * @return string
     */
    function e($value): string
    {
        return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
    }
}

