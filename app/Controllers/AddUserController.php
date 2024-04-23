<?php

namespace App\Controllers;

use App\Entities\User;
use App\Services\UserService;
use Modes\Framework\Controller\AbstractController;
use Modes\Framework\Http\RedirectResponse;
use Modes\Framework\Http\Response;

class AddUserController extends AbstractController
{
    public function __construct(
        private UserService $userService,
    )
    {
    }

    public function __invoke(): Response
    {
        $data = $this->request->getData();
        $user = User::create(
            name: $data['name'],
            email: $data['email'],
            password: $data['password']
        );

        $user = $this->userService->save($user);

        return new RedirectResponse("/users/{$user->getId()}");
    }
}