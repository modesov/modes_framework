<?php

namespace App\Controllers;

use Modes\Framework\Authentication\SessionAuthInterface;
use Modes\Framework\Controller\AbstractController;
use Modes\Framework\Http\RedirectResponse;
use Modes\Framework\Http\Response;

class LogoutController extends AbstractController
{
    public function __construct(
        private SessionAuthInterface $sessionAuth,
    )
    {
    }

    public function __invoke(): Response
    {
        $this->sessionAuth->logout();
        return new RedirectResponse('/');
    }
}