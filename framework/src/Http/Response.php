<?php

namespace Modes\Framework\Http;

class Response
{
    public function __construct(
        private readonly mixed $content,
        private readonly int   $statusCode = 200,
        private array          $headers = [],
    )
    {
        http_response_code($this->statusCode);
    }

    public function send(): void
    {
        echo $this->content;
    }
}