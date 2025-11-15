<?php

namespace Luminode\Core\Middleware;

use Closure;
use Luminode\Core\Response;
use DI\Container;

/**
 * Interface MiddlewareInterface
 * Defines the contract for a middleware component.
 */
interface MiddlewareInterface
{
    /**
     * Handle the incoming request and pass it to the next middleware in the pipeline.
     *
     * @param Container $container The dependency injection container.
     * @param Closure $next A closure that represents the next middleware layer.
     * @return Response The HTTP response.
     */
    public function handle(Container $container, Closure $next): Response;
}
