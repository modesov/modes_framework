<?php

namespace Modes\Framework\Routing;

use FastRoute\Dispatcher;
use FastRoute\RouteCollector;
use Modes\Framework\Http\Request;
use Modes\Framework\Http\Responses\NotAllowedMethodResponse;
use Modes\Framework\Http\Responses\NotFountResponse;
use function FastRoute\simpleDispatcher;

class Router implements RouterInterface
{
    public function dispatch(Request $request): array
    {
        $dispatcher = simpleDispatcher(function (RouteCollector $collector) {
            $routes = include BASE_PATH . '/routes/web.php';

            foreach ($routes as $route) {
                $collector->addRoute(...$route);
            }
        });

        $routeInfo = $dispatcher->dispatch($request->getMethod(), $request->getPath());

        switch ($routeInfo[0]) {
            case Dispatcher::NOT_FOUND:
                return [[new NotFountResponse, 'index'], []];
            case Dispatcher::METHOD_NOT_ALLOWED:
                $allowedMethods = implode(', ', $routeInfo[1]);
                return [[new NotAllowedMethodResponse, 'index'], compact('allowedMethods')];
            default:
                [, [$controller, $method], $vars] = $routeInfo;
                return [[new $controller, $method], $vars];
        }

    }
}