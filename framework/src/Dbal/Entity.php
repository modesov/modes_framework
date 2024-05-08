<?php

namespace Modes\Framework\Dbal;

abstract class Entity
{
    abstract public function setId(?int $id): void;
}