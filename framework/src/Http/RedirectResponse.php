<?php

namespace Modes\Framework\Http;

class RedirectResponse extends Response
{
    public function __construct(string $url)
    {
        parent::__construct(statusCode: 302, headers: ['location' => $url]);
    }

    public function send(): void
    {
        header("Location: {$this->getHeader('location')}", true, $this->getStatusCode());
        exit;
    }
}