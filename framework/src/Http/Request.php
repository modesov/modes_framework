<?php

namespace Modes\Framework\Http;

use Modes\Framework\Session\SessionInterface;

class Request
{
    private SessionInterface $session;

    public function __construct(
        private readonly array $qetParams,
        private readonly array $postData,
        private readonly array $cookies,
        private readonly array $files,
        private readonly array $server
    )
    {
    }

    public function setSession(SessionInterface $session)
    {
        $this->session = $session;
    }

    public function getSession(): SessionInterface
    {
        return $this->session;
    }

    public static function createFromGlobals(): static
    {
        return new static($_GET, $_POST, $_COOKIE, $_FILES, $_SERVER);
    }

    public function getPath(): string
    {
        return strtok($_SERVER['REQUEST_URI'], '?');
    }

    public function getMethod()
    {
        return $this->server['REQUEST_METHOD'];
    }

    public function getData(): array
    {
        return $this->postData ?: [];
    }
}
