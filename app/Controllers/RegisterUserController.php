<?php

namespace App\Controllers;

use App\Forms\User\RegisterForm;
use App\Services\UserService;
use Modes\Framework\Authentication\SessionAuthInterface;
use Modes\Framework\Controller\AbstractController;
use Modes\Framework\Http\RedirectResponse;
use Modes\Framework\Http\Response;

class RegisterUserController extends AbstractController
{
    public function __construct(
        private RegisterForm $form,
        private SessionAuthInterface $auth
    )
    {
    }

    public function __invoke(): Response
    {
        $this->form->setFields(
            name: $this->request->input('name'),
            email: $this->request->input('email'),
            password: $this->request->input('password'),
            passwordConfirmation: $this->request->input('password_confirmation'),
        );

        if ($this->form->hasValidationErrors()) {
            foreach ($this->form->getValidationErrors() as $error) {
                $this->request->getSession()->setFlash('error', $error);
            }

            // TODO надо реализовать сохранение введенных пользователем данных в сессию при пройденной валидации

            return new RedirectResponse('/');
        }

        $user = $this->form->save();

        $this->request->getSession()->setFlash('success', 'Пользователь успешно зарегистрирован!');

        $this->auth->login($user);

        return new RedirectResponse("/dashboard");
    }
}