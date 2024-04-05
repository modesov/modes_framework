<?php

namespace Modes\Framework\Http;

use FastRoute\RouteCollector;
use FastRoute\Dispatcher;
use function FastRoute\simpleDispatcher;

class Kernel
{
    public function handle(Request $request): Response
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
                $response = new Response('404 Not Found', 404);
                break;
            case Dispatcher::METHOD_NOT_ALLOWED:
                $allowedMethods = $routeInfo[1];
                $response = new Response(
                    content: 'Не разрешенный метод. Разрешены следующие методы - ' . implode(', ', $allowedMethods),
                    statusCode: 405
                );
                break;
            default:
                [, $handler, $vars] = $routeInfo;
                $response = $handler($vars);
        }

        return $response;
    }
}