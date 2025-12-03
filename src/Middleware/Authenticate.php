<?php
/*
 * @Author: 7123
 * @Date: 2025-11-15 19:46:15
 * @LastEditors: 7123
 * @LastEditTime: 2025-12-03 19:17:13
 */

namespace Luminode\Core\Middleware;

use Closure;
use DI\Container;
use Luminode\Core\Auth;
use Luminode\Core\Response;

class Authenticate implements MiddlewareInterface
{
    protected Auth $auth;

    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    /**
     * 处理传入的请求
     *
     * @param Container $container 依赖注入容器
     * @param Closure $next 代表下一个中间件层的闭包
     * @return Response HTTP 响应
     */
    public function handle(Container $container, Closure $next): Response
    {
        if ($this->auth->check()) {
            return $next($container);
        }

        // User is not authenticated, redirect to the login page.
        // In a real app, you might want to make the redirect path configurable.
        return new Response('', 302, ['Location' => '/login']);
    }
}
