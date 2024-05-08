<?php

namespace Modes\Framework\Http;

class Response
{
    public function __construct(
        private string $content = '',
        private int    $statusCode = 200,
        private array  $headers = [],
    )
    {
        http_response_code($this->statusCode);
    }
    public function send(): void
    {
        echo $this->content;
    }

    public function getContent(): string
    {
        return $this->content;
    }
    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function getHeader(string $key): ?string
    {
        return $this->headers[$key] ?? null;
    }

    public function setHeader(string $key, mixed $value): void
    {
        $this->headers[$key] = $value;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function setStatusCode(int $statusCode): void
    {
        $this->statusCode = $statusCode;
    }
}