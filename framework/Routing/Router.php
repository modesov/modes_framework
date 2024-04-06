<?php

namespace Modes\Framework\Routing;

use FastRoute\Dispatcher;
use FastRoute\RouteCollector;
use Modes\Framework\Http\Exceptions\MethodNotAllowedException;
use Modes\Framework\Http\Exceptions\NotFoundException;
use Modes\Framework\Http\Request;
use function FastRoute\simpleDispatcher;

class Router implements RouterInterface
{
    /**
     * @throws MethodNotAllowedException
     * @throws NotFoundException
     */
    public function dispatch(Request $request): array
    {
        [[$controller, $methods], $vars] = $this->extractRouteInfo($request);

        return [[new $controller, $methods], $vars];
    }

    /**
     * @throws MethodNotAllowedException
     * @throws NotFoundException
     */
    private function extractRouteInfo(Request $request): array
    {
        $dispatcher = simpleDispatcher(function (RouteCollector $collector) {
            $routes = include BASE_PATH . '/routes/web.php';

            foreach ($routes as $route) {
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