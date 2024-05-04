<?php

namespace Modes\Framework\Http\Middlewares;

use Modes\Framework\Http\Middlewares\RequestHandlerInterface;
use Modes\Framework\Http\Request;
use Modes\Framework\Http\Response;
use Psr\Container\ContainerInterface;

class RequestHandler implements RequestHandlerInterface
{
    private array $middlewares = [
        StartSession::class,
        ExtractRouteInfo::class,
    ];

    public function __construct(
        private ContainerInterface $container
    )
    {
    }

    public function injectionMiddlewares(array $middlewares): void
    {
        $this->middlewares = array_merge($middlewares, $this->middlewares);
    }
    public function handle(Request $request): Response
    {
        if (empty($this->middlewares)) {
            return $this->container->get(RouterDispatch::class)->process($request, $this);
        }

        $middlewareClass = array_shift($this->middlewares);

        $middleware = $this->container->get($middlewareClass);

        return $middleware->process($request, $this);
    }
}