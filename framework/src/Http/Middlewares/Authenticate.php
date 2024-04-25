<?php

namespace Modes\Framework\Http\Middlewares;

use Modes\Framework\Http\Middlewares\MiddlewareInterface;
use Modes\Framework\Http\Request;
use Modes\Framework\Http\Response;

class Authenticate implements MiddlewareInterface
{
    private bool $isAuthenticated = true;

    public function process(Request $request, RequestHandlerInterface $handler): Response
    {
        if (!$this->isAuthenticated) {
            return new Response('Unauthorized', 401);
        }

        return $handler->handle($request);
    }
}