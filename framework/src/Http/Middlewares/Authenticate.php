<?php

namespace Modes\Framework\Http\Middlewares;

use Modes\Framework\Http\Middlewares\MiddlewareInterface;
<<<<<<< HEAD
=======
use Modes\Framework\Authentication\SessionAuthInterface;
use Modes\Framework\Http\RedirectResponse;
>>>>>>> 7e1ed4d (implement registration authentication)
use Modes\Framework\Http\Request;
use Modes\Framework\Http\Response;

class Authenticate implements MiddlewareInterface
{
<<<<<<< HEAD
    private bool $isAuthenticated = true;

    public function process(Request $request, RequestHandlerInterface $handler): Response
    {
        if (!$this->isAuthenticated) {
            return new Response('Unauthorized', 401);
=======
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
>>>>>>> 7e1ed4d (implement registration authentication)
        }

        return $handler->handle($request);
    }
}