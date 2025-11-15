<?php

namespace Luminode\Core\Middleware;

use Closure;
use DI\Container;
use Luminode\Core\Exceptions\CsrfTokenMismatchException;
use Luminode\Core\Response;

class CsrfMiddleware implements MiddlewareInterface
{
    /**
     * Handle an incoming request.
     *
     * @param Container $container
     * @param Closure $next
     * @return Response
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
     * Determine if the request is a reading request.
     *
     * @return bool
     */
    protected function isReadingRequest(): bool
    {
        return in_array(strtoupper($_SERVER['REQUEST_METHOD']), ['HEAD', 'GET', 'OPTIONS']);
    }

    /**
     * Determine if the session and request CSRF tokens match.
     *
     * @return bool
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
     * Generate and return a CSRF token.
     *
     * @return string
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
