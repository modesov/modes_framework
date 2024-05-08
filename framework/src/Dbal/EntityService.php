<?php

namespace Modes\Framework\Dbal;

use Doctrine\DBAL\Connection;
use Modes\Framework\Dbal\Events\EntityPersist;
use Psr\EventDispatcher\EventDispatcherInterface;

class EntityService
{
    public function __construct(
        private Connection $connection,
        private EventDispatcherInterface $eventDispatcher,
    )
    {
    }

    public function getConnection(): Connection
    {
        return $this->connection;
    }

    public function save(Entity $entity): int
    {
        $id = $this->connection->lastInsertId();
        $entity->setId($id);
        $this->eventDispatcher->dispatch(new EntityPersist($entity));
        return $id;
    }
}