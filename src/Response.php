<?php
/*
 * @Author: 7123
 * @Date: 2025-11-15 18:58:49
 * @LastEditors: 7123
 * @LastEditTime: 2025-12-03 19:21:24
 */

namespace Luminode\Core;

class Response
{
    /**
     * @param string $content 响应内容
     * @param int $statusCode 状态码
     * @param array $headers 响应头
     */
    public function __construct(
        private string $content = '',
        private int $statusCode = 200,
        private array $headers = []
    ) {}

    /**
     * 将响应发送给客户端
     *
     * @return void
     */
    public function send(): void
    {
        // Send status code
        http_response_code($this->statusCode);

        // Send headers
        foreach ($this->headers as $name => $value) {
            header("$name: $value");
        }

        // Send content
        echo $this->content;
    }

    /**
     * 获取响应内容
     * @return string 响应内容
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * 获取状态码
     * @return int 状态码
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * 获取响应头
     * @return array 响应头数组
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }
}
