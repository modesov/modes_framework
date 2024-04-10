<?php

namespace Modes\Framework\Routing;

use FastRoute\Dispatcher;
use FastRoute\RouteCollector;
use League\Container\Container;
use Modes\Framework\Http\Exceptions\MethodNotAllowedException;
use Modes\Framework\Http\Exceptions\NotFoundException;
use Modes\Framework\Http\Request;
use function FastRoute\simpleDispatcher;

class Router implements RouterInterface
{
    private array $routes = [];

    /**
     * @throws MethodNotAllowedException
     * @throws NotFoundException
     */
    public function dispatch(Request $request, Container $container): array
    {
        [$handler, $vars] = $this->extractRouteInfo($request);

        if (is_array($handler)) {
            [$controllerId, $method] = $handler;
            $controller = $container->get($controllerId);
            $handler = [$controller, $method];
        }

        if (is_string($handler)) {
            $handler = $container->get($handler);
        }

        return [$handler, $vars];
    }

    public function registerRoutes(array $routes): void
    {
        $this->routes = array_merge($this->routes, $routes);
    }

    /**
     * @throws MethodNotAllowedException
     * @throws NotFoundException
     */
    private function extractRouteInfo(Request $request): array
    {
        $dispatcher = simpleDispatcher(function (RouteCollector $collector) {
            foreach ($this->routes as $route) {
                $collector->addRoute(...$route);
            }
        });

        $routeInfo = $dispatcher->dispatch($request->getMethod(), $request->getPath());

        switch ($routeInfo[0]) {
            case Dispatcher::FOUND:
                [, $handler, $vars] = $routeInfo;
                return [$handler, $vars];
            case Dispatcher::METHOD_NOT_ALLOWED:
                throw new MethodNotAllowedException(message: 'Method not allowed', allowedMethods: $routeInfo[1]);
            default:
                throw new NotFoundException(message: "404 not found");
        }
    }
}