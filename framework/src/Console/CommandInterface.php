<?php

namespace Modes\Framework\Console;

interface CommandInterface
{
    public function execute(array $parameters = []): int;
}