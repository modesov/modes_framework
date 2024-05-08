<?php

namespace App\Entities;

use Modes\Framework\Authentication\AuthUserInterface;
use Modes\Framework\Dbal\Entity;

class User extends Entity implements AuthUserInterface
{
    private function __construct(
        private ?int $id,
        private string $name,
        private string $email,
        private string $password,
        private ?\DateTimeImmutable $createdAt,
    )
    {
    }

    public static function create(
        string $name,
        string $email,
        string $password,
        ?\DateTimeImmutable $createdAt = null,
        ?int $id = null
    ): static
    {
        return new static($id, $name, $email, $password, $createdAt ?? new \DateTimeImmutable());
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function setCreatedAt(?\DateTimeImmutable $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }
}