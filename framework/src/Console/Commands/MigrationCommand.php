<?php

namespace Modes\Framework\Console\Commands;

use Modes\Framework\Console\CommandInterface;

class MigrationCommand extends AbstractCommand implements CommandInterface
{
    private string $name = "migrate";

    protected array $help = [
        'help' => 'Выводит список доступных комманд с описанием',
        'send' => 'Может принимать значения email | sms. Пример --send=email отправит сообщение об успешной мигрции на email',
    ];

    public function execute(array $parameters = []): int
    {
        if ($parameters['help']) {
            echo $this->getHelp($parameters);
        }

        return 0;
    }
}