<?php

namespace Luminode\Core\Middleware;

use Closure;
use DI\Container;
use Luminode\Core\Response;

class CheckForMaintenanceMode implements MiddlewareInterface
{
    public function handle(Container $container, Closure $next): Response
    {
        $file = APP_ROOT . '/storage/framework/down';

        if (file_exists($file)) {
            // 如果存在 down 文件，直接返回 503
            $data = json_decode(file_get_contents($file), true);
            $message = $data['message'] ?? 'Service Unavailable';
            
            return new Response(
                "<h1>503 Service Unavailable</h1><p>{$message}</p>",
                503
            );
        }

        return $next($container);
    }
}
