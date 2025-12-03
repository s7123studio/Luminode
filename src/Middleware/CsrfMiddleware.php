<?php
/*
 * @Author: 7123
 * @Date: 2025-03-09 22:43:28
 * @LastEditors: 7123
 * @LastEditTime: 2025-12-03 19:17:35
 */

namespace Luminode\Core\Middleware;

use Closure;
use DI\Container;
use Luminode\Core\Exceptions\CsrfTokenMismatchException;
use Luminode\Core\Response;

class CsrfMiddleware implements MiddlewareInterface
{
    /**
     * 处理传入的请求
     *
     * @param Container $container 依赖注入容器
     * @param Closure $next 代表下一个中间件层的闭包
     * @return Response HTTP 响应
     * @throws CsrfTokenMismatchException
     */
    public function handle(Container $container, Closure $next): Response
    {
        if ($this->isReadingRequest() || $this->tokensMatch()) {
            return $next($container);
        }

        throw new CsrfTokenMismatchException('CSRF token mismatch.');
    }

    /**
     * 判断请求是否为读取请求
     *
     * @return bool 是否为读取请求
     */
    protected function isReadingRequest(): bool
    {
        return in_array(strtoupper($_SERVER['REQUEST_METHOD']), ['HEAD', 'GET', 'OPTIONS']);
    }

    /**
     * 判断会话和请求的 CSRF 令牌是否匹配
     *
     * @return bool 是否匹配
     */
    protected function tokensMatch(): bool
    {
        $token = $_POST['_token'] ?? '';

        $sessionToken = $_SESSION['csrf_token'] ?? null;

        if (!is_string($sessionToken) || !is_string($token)) {
            return false;
        }

        if (empty($sessionToken) || empty($token)) {
            return false;
        }

        return hash_equals($sessionToken, $token);
    }

    /**
     * 生成并返回 CSRF 令牌
     *
     * @return string CSRF 令牌
     * @throws \Exception
     */
    public static function generateToken(): string
    {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }
}
