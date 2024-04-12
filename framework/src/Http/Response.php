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

    /**
     * @param string $content
     */
    public function setContent(string $content): void
    {
        $this->content = $content;
    }
}