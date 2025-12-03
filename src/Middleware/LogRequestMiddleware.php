<?php
/*
 * @Author: 7123
 * @Date: 2025-11-15 19:05:29
 * @LastEditors: 7123
 * @LastEditTime: 2025-12-03 19:17:41
 */

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
