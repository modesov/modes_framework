<?php

namespace App\Forms\User;


use App\Entities\User;
use Modes\Framework\Authentication\UserServiceInterface;

class RegisterForm
{
    public function __construct(
        private UserServiceInterface $userService
    )
    {
    }

    private string $name;
    private string $email;
    private string $password;
    private string $passwordConfirmation;

    public function setFields(
        string $name,
        string $email,
        string $password,
        string $passwordConfirmation
    ): void
    {
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
        $this->passwordConfirmation = $passwordConfirmation;
    }

    public function save(): User
    {
        $user = User::create(
            name: $this->name,
            email: $this->email,
            password: password_hash($this->password, PASSWORD_DEFAULT),
            createdAt: new \DateTimeImmutable()
        );

        return $this->userService->store($user);
    }


    // TODO это гов.. надо переделывать или использовать какую то библиотеку
    public function getValidationErrors(): array
    {
        $errors = [];

        if (empty($this->name)) {
            $errors[] = 'Имя обязательное поле';
        }

        if (strlen($this->name) > 50) {
            $errors[] = 'Максимальная длина имени 50 символов';
        }

        if (empty($this->email)) {
            $errors[] = 'Email обязательное поле';
        }

        if (!empty($this->email) && !filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Неверный формат почты';
        }

        if (empty($this->password) || strlen($this->password) < 8) {
            $errors[] = 'Пароль должен быть не менее 8 символов';
        }

        if ($this->passwordConfirmation !== $this->password) {
            $errors[] = 'Подтвержвение пароля не совподает с паролем';
        }

        return $errors;
    }

    public function hasValidationErrors(): bool
    {
        return !empty($this->getValidationErrors());
    }

}