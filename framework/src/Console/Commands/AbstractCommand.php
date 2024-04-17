<?php

namespace Modes\Framework\Console\Commands;

class AbstractCommand
{
    protected array $help = [];

    protected function getHelp($parameters = []): string
    {
        if (!empty($parameters)) {
            unset($parameters['help']);
        }

        $str = '';
        foreach ($this->help as $key => $value) {
            if (!empty($parameters) && !array_key_exists($key, $parameters)) {
                continue;
            }

            $str .= "--$key: $value\n";
        }
        return $str;
    }
}