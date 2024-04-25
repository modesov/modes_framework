<?php

namespace Modes\Framework\Http\Middlewares;

use Modes\Framework\Http\Request;
use Modes\Framework\Http\Response;

interface MiddlewareInterface
{
    public function process(Request $request, RequestHandlerInterface $handler): Response;
}