<?php

namespace Modes\Framework\Routing;

use League\Container\Container;
use Modes\Framework\Http\Exceptions\MethodNotAllowedException;
use Modes\Framework\Http\Exceptions\NotFoundRouteException;
use Modes\Framework\Http\Request;

interface  RouterInterface
{
    /**
     * @throws MethodNotAllowedException
     * @throws NotFoundRouteException
     */
    public function dispatch(Request $request, Container $container): array;

    public function registerRoutes(array $routes): void;
}