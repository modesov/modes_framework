<?php

namespace Modes\Framework\Routing;

use League\Container\Container;
use Modes\Framework\Http\Request;

interface  RouterInterface
{
    public function dispatch(Request $request, Container $container): array;
}