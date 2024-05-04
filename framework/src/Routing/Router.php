<?php

namespace Modes\Framework\Routing;

use Modes\Framework\Controller\AbstractController;
use Modes\Framework\Http\Request;
use Psr\Container\ContainerInterface;

class Router implements RouterInterface
{
    public function dispatch(Request $request, ContainerInterface $container): array
    {
        $handler = $request->getRouteHandler();
        $vars = $request->getRouteArgs();

        if (is_array($handler)) {
            [$controllerId, $method] = $handler;
            $controller = $container->get($controllerId);
            $this->setRequest($controller, $request);
            $handler = [$controller, $method];
        }

        if (is_string($handler)) {
            $handler = $container->get($handler);
            $this->setRequest($handler, $request);
        }

        return [$handler, $vars];
    }

    private function setRequest(object $object, Request $request): void
    {
        if (is_subclass_of($object, AbstractController::class)) {
            $object->setRequest($request);
        }
    }
}