<?php

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
     * Handle an incoming request.
     *
     * @param Container $container
     * @param Closure $next
     * @return Response
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
