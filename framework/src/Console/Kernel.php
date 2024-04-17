<?php

namespace Modes\Framework\Console;

use Psr\Container\ContainerInterface;

class Kernel
{
    public function __construct(
        private ContainerInterface $container,
        private Application        $application
    )
    {
    }

    public function handler(): int
    {
        $this->registerCommands();

        return $this->application->run();
    }

    private function registerCommands(): void
    {
        $commandFiles = new \DirectoryIterator(__DIR__ . '/Commands');
        $frameworkCommandNamespace = $this->container->get('framework-command-namespace');

        /** @var \DirectoryIterator $commandFile */
        foreach ($commandFiles as $commandFile) {
            if (!$commandFile->isFile()) {
                continue;
            }

            $nameClass = pathinfo($commandFile, PATHINFO_FILENAME);

            $command = $frameworkCommandNamespace . $nameClass;

            if (is_subclass_of($command, CommandInterface::class)) {
                $reflection = new \ReflectionClass($command);
                $nameCommand = $reflection->hasProperty('name')
                    ? $reflection->getProperty('name')->getDefaultValue()
                    : strtolower(str_replace('Command', '', $nameClass));

                $this->container->add("mc:$nameCommand", $command);
            }
        }
    }

}