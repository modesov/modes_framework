<?php

namespace Modes\Framework\Dbal\Events;

use Modes\Framework\Dbal\Entity;
use Modes\Framework\Event\Event;

class EntityPersist extends Event
{
    public function __construct(
        private Entity $entity
    )
    {
    }

    public function getEntity(): Entity
    {
        return $this->entity;
    }

}