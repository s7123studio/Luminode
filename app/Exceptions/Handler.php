<?php
/*
 * @Description: 
 * @Author: 7123
 * @version: 
 * @Date: 2025-12-08 02:57:28
 * @LastEditors: 7123
 * @LastEditTime: 2025-12-08 03:46:54
 */

namespace App\Exceptions;

use Luminode\Core\Response;
use Luminode\Core\Exceptions\RouteNotFoundException;
use Luminode\Core\Exceptions\CsrfTokenMismatchException;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Throwable;

class Handler
{
    public function render(Throwable $e): Response
    {
        // 如果是调试模式，让 Whoops 接管所有异常（包括 404），方便调试路由
        if (config('app.debug')) {
            throw $e;
        }

        if ($e instanceof RouteNotFoundException) {
            return $this->response('404 Not Found', 404);
        }

        if ($e instanceof CsrfTokenMismatchException) {
            return $this->response('Page Expired', 419);
        }

        // 默认处理：500 错误
        $this->logError($e);

        return $this->response('Server Error', 500);
    }

    protected function response($message, $status): Response
    {
        if ($this->isJsonRequest()) {
            return new Response(json_encode(['error' => $message]), $status, ['Content-Type' => 'application/json']);
        }
        return new Response("<h1>{$status}</h1><p>{$message}</p>", $status);
    }

    protected function isJsonRequest(): bool
    {
        return isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false;
    }

    protected function logError(Throwable $e)
    {
        $log = app(\Psr\Log\LoggerInterface::class);
        $log->error($e->getMessage(), ['exception' => $e]);
    }
}
//TODO: 还不知道缺啥，但是这个框架确实缺点啥