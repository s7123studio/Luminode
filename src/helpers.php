<?php

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

if (!function_exists('config')) {
    /**
     * 获取配置值 (简单的示例，实际可能需要 Config 服务)
     * 暂时只从 $_ENV 获取
     */
    function config($key, $default = null)
    {
        return $_ENV[$key] ?? $default;
    }
}
