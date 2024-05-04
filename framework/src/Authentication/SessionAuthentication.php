<?php

namespace Modes\Framework\Authentication;

use Modes\Framework\Authentication\SessionAuthInterface;
use Modes\Framework\Session\Session;
use Modes\Framework\Session\SessionInterface;

class SessionAuthentication implements SessionAuthInterface
{

    private AuthUserInterface $user;

    public function __construct(
        private UserServiceInterface $userService,
        private SessionInterface $session
    )
    {
    }

    public function authenticate(string $email, string $password): bool
    {
        $user = $this->userService->getUserByEmail($email);

        if (!$user || !password_verify($password, $user->getPassword())) {
            return false;
        }

        $this->login($user);

        return true;
    }

    public function login(AuthUserInterface $user): void
    {
        $this->session->set(Session::AUTH_KEY, $user->getId());

        $this->user = $user;
    }

    public function logout(): void
    {
        $this->session->remove(Session::AUTH_KEY);
    }

    public function getUser(): ?AuthUserInterface
    {
        return $this->user;
    }

    public function checkAuth(): bool
    {
        return $this->session->has(Session::AUTH_KEY);
    }
}