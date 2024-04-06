<?php

namespace Modes\Framework\Routing;

class Route
{
    public static function get(string $uri, Callable | array $handler): array
    {
        return ['GET', $uri, $handler];
    }

    public static function post(string $uri, Callable | array $handler): array
    {
        return ['POST', $uri, $handler];
    }
}