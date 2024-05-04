<?php

namespace Modes\Framework\Http;

use Modes\Framework\Session\SessionInterface;

class Request
{
    private SessionInterface $session;

<<<<<<< HEAD
=======
    private mixed $routeHandler;

    private array $routeArgs;

>>>>>>> 7e1ed4d (implement registration authentication)
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

<<<<<<< HEAD
=======
    public function getRouteHandler(): mixed
    {
        return $this->routeHandler;
    }

    public function setRouteHandler(mixed $routeHandler): void
    {
        $this->routeHandler = $routeHandler;
    }

    public function getRouteArgs(): array
    {
        return $this->routeArgs;
    }

    public function setRouteArgs(array $routeArgs): void
    {
        $this->routeArgs = $routeArgs;
    }

>>>>>>> 7e1ed4d (implement registration authentication)
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

    public function input(string $key, $default = null)
    {
        return $this->postData[$key] ?? $default;
    }
}
