<?php

namespace Modes\Framework\Routing;

class Route
{
    public static function get(string $uri, Callable $handler): array
    {
        return ['GET', $uri, $handler];
    }

    public static function post(string $uri, Callable $handler): array
    {
        return ['POST', $uri, $handler];
    }
}