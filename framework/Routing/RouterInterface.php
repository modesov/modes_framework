<?php

namespace Modes\Framework\Routing;

use Modes\Framework\Http\Request;

interface RouterInterface
{
    public function dispatch(Request $request): array;
}