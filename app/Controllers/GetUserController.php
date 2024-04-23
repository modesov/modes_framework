<?php

namespace App\Controllers;

use App\Services\UserService;
use Modes\Framework\Controller\AbstractController;
use Modes\Framework\Http\Response;

class GetUserController extends AbstractController
{

    public function __construct(
        private UserService $userService,
    )
    {
    }

    public function __invoke(int $id): Response
    {
        $user = $this->userService->findOrFail($id);
        return $this->render('users/user', ['user' => $user]);
    }

}