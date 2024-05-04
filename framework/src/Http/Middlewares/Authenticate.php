<?php

namespace Modes\Framework\Http\Middlewares;

use Modes\Framework\Http\Middlewares\MiddlewareInterface;
use Modes\Framework\Authentication\SessionAuthInterface;
use Modes\Framework\Http\RedirectResponse;
use Modes\Framework\Http\Request;
use Modes\Framework\Http\Response;

class Authenticate implements MiddlewareInterface
{
    public function __construct(
        private SessionAuthInterface $auth,
    )
    {
    }

    public function process(Request $request, RequestHandlerInterface $handler): Response
    {
        if (!$this->auth->checkAuth()) {
            $request->getSession()->setFlash('error_login', 'You need to sign in your account');
            return new RedirectResponse('/');
        }

        return $handler->handle($request);
    }
}