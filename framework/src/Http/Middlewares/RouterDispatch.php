<?php

namespace Modes\Framework\Http\Middlewares;

use Modes\Framework\Http\Middlewares\MiddlewareInterface;
use Modes\Framework\Http\Request;
use Modes\Framework\Http\Response;
use Modes\Framework\Routing\RouterInterface;
use Psr\Container\ContainerInterface;

class RouterDispatch implements MiddlewareInterface
{
    public function __construct(
        private RouterInterface $router,
        private ContainerInterface $container
    )
    {
    }

    public function process(Request $request, RequestHandlerInterface $handler): Response
    {
        [$handler, $vars] = $this->router->dispatch($request, $this->container);
        return call_user_func_array($handler, $vars);
    }
}