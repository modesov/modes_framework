<?php

namespace Modes\Framework\Dbal;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;

class ConnectionFactory
{

    public function __construct(
        private readonly array $databaseConfiguration
    )
    {
    }


    public function create(): Connection
    {
        return DriverManager::getConnection($this->databaseConfiguration);
    }
}
