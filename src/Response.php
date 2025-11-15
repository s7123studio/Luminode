<?php

namespace Luminode\Core;

class Response
{
    /**
     * @param string $content
     * @param int $statusCode
     * @param array $headers
     */
    public function __construct(
        private string $content = '',
        private int $statusCode = 200,
        private array $headers = []
    ) {}

    /**
     * Sends the response to the client.
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
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @return int
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * @return array
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }
}
