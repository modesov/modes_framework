<?php

namespace Modes\Framework\Authentication;

interface UserServiceInterface
{
    public function getUserByEmail(string $email): ?AuthUserInterface;
}