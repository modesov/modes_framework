<?php

namespace App\Controllers;

use Modes\Framework\Authentication\SessionAuthInterface;
use Modes\Framework\Controller\AbstractController;
use Modes\Framework\Http\RedirectResponse;
use Modes\Framework\Http\Response;

class LoginController extends AbstractController
{
    public function __construct(
        private SessionAuthInterface $sessionAuth,
    )
    {
    }

    public function __invoke(): Response
    {
        $result = $this->sessionAuth->authenticate(
            $this->request->input('email'),
            $this->request->input('password')
        );

        if (!$result) {
            $this->request->getSession()->setFlash('error_login', 'Неверный логин или пароль!');
            return new RedirectResponse('/');
        }

        $user = $this->sessionAuth->getUser();

        $this->request->getSession()->setFlash('success', "{$user->getName()}, поздравляем! Вы успешно авторизовались!");
        return new RedirectResponse('/dashboard');
    }
}