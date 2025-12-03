<?php
/*
 * @Author: 7123
 * @Date: 2025-11-15 19:04:28
 * @LastEditors: 7123
 * @LastEditTime: 2025-12-03 19:17:53
 */

namespace Luminode\Core\Middleware;

use Closure;
use Luminode\Core\Response;
use DI\Container;

/**
 * MiddlewareInterface 接口
 * 定义中间件组件的契约
 */
interface MiddlewareInterface
{
    /**
     * 处理传入的请求并将其传递给管道中的下一个中间件
     *
     * @param Container $container 依赖注入容器
     * @param Closure $next 代表下一个中间件层的闭包
     * @return Response HTTP 响应
     */
    public function handle(Container $container, Closure $next): Response;
}
