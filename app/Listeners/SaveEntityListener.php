<?php

namespace App\Listeners;

use Modes\Framework\Dbal\Events\EntityPersist;

class SaveEntityListener
{
    public function __invoke(EntityPersist $event): void
    {
//        dd($event->getEntity());
    }
}