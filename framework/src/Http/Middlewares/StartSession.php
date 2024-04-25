<?php

namespace Modes\Framework\Http\Middlewares;

use Modes\Framework\Http\Middlewares\MiddlewareInterface;
use Modes\Framework\Http\Request;
use Modes\Framework\Http\Response;
use Modes\Framework\Session\SessionInterface;

class StartSession implements MiddlewareInterface
{
    public function __construct(
        private SessionInterface $session
    )
    {
    }

    public function process(Request $request, RequestHandlerInterface $handler): Response
    {
        $this->session->start();

        $request->setSession($this->session);

        return $handler->handle($request);
    }

}