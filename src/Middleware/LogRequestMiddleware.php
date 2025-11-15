<?php

namespace Luminode\Core\Middleware;

use Closure;
use DI\Container;
use Luminode\Core\Response;
use Psr\Log\LoggerInterface;

class LogRequestMiddleware implements MiddlewareInterface
{
    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function handle(Container $container, Closure $next): Response
    {
        $method = $_SERVER['REQUEST_METHOD'] ?? 'UNKNOWN';
        $uri = $_SERVER['REQUEST_URI'] ?? '/';

        $this->logger->info("Request received: {$method} {$uri}");

        // Proceed to the next layer in the pipeline
        $response = $next($container);

        $this->logger->info("Response sent: {$response->getStatusCode()}");

        return $response;
    }
}
