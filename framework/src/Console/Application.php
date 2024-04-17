<?php

namespace Modes\Framework\Console;

use Modes\Framework\Console\Exceptions\ConsoleException;
use Psr\Container\ContainerInterface;

class Application
{
    public function __construct(
        private ContainerInterface $container
    )
    {
    }

    public function run(): int
    {
        $argv = $_SERVER['argv'];
        $commandName = $argv[1] ?? null;

        if ($commandName === null) {
            throw new ConsoleException('Invalid command name');
        }

        /** @var CommandInterface $command */
        $command = $this->container->get("mc:$commandName");

        $args = array_slice($argv, offset: 2);
        $parameters = $this->parseParameters(args: $args);
        return $command->execute($parameters);
    }

    private function parseParameters(array $args): array
    {
        $parameters = [];
        foreach ($args as $arg) {
            if (!str_starts_with($arg, '--')) {
                continue;
            }

            $arg = explode(separator: '=', string: substr(string: $arg, offset: 2));

            $parameters[$arg[0]] = $arg[1] ?? true;
        }

        return $parameters;
    }
}