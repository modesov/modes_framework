<?php

namespace Modes\Framework\Routing;

use Modes\Framework\Http\Request;
use Psr\Container\ContainerInterface;

interface  RouterInterface
{
    public function dispatch(Request $request, ContainerInterface $container): array;
}