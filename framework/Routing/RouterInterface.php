<?php

namespace Modes\Framework\Routing;

use Modes\Framework\Http\Exceptions\MethodNotAllowedException;
use Modes\Framework\Http\Exceptions\NotFoundException;
use Modes\Framework\Http\Request;

interface RouterInterface
{
    /**
     * @throws MethodNotAllowedException
     * @throws NotFoundException
     */
    public function dispatch(Request $request): array;
}