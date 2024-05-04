<?php

namespace Modes\Framework\Http\Middlewares;

use FastRoute\Dispatcher;
use FastRoute\RouteCollector;
use Modes\Framework\Http\Exceptions\MethodNotAllowedException;
use Modes\Framework\Http\Exceptions\NotFoundRouteException;
use Modes\Framework\Http\Middlewares\MiddlewareInterface;
use Modes\Framework\Http\Request;
use Modes\Framework\Http\Response;
use function FastRoute\simpleDispatcher;

class ExtractRouteInfo implements MiddlewareInterface
{
    public function __construct(
        private array $routes
    )
    {
    }

    public function process(Request $request, RequestHandlerInterface $handler): Response
    {
        $dispatcher = simpleDispatcher(function (RouteCollector $collector) {
            foreach ($this->routes as $route) {
                $collector->addRoute(...$route);
            }
        });

        $routeInfo = $dispatcher->dispatch($request->getMethod(), $request->getPath());

        switch ($routeInfo[0]) {
            case Dispatcher::FOUND:
                [, [$routeHandler, $middlewares], $vars] = $routeInfo;
                $request->setRouteHandler($routeHandler);
                $request->setRouteArgs($vars);
                $handler->injectionMiddlewares($middlewares);
                break;
            case Dispatcher::METHOD_NOT_ALLOWED:
                throw new MethodNotAllowedException(message: 'Method not allowed', allowedMethods: $routeInfo[1]);
            default:
                throw new NotFoundRouteException(message: "404 not found");
        }

        return $handler->handle($request);
    }
}