<?php

namespace Modes\Framework\Authentication;

interface AuthUserInterface
{
    public function getPassword(): string;

    public function getId(): ?int;

    public function getEmail(): string;

    public function getName(): string;
}