<?php

namespace Modes\Framework\Event;

use Psr\EventDispatcher\ListenerProviderInterface;

class ListenerProvider implements ListenerProviderInterface
{

    private array $Listeners = [];

    public function getListenersForEvent(object $event): iterable
    {
        $className = get_class($event);
        return $this->Listeners[$className] ?? [];
    }

    public function addListener(string $eventName, callable $listener): static
    {
        $this->Listeners[$eventName][] = $listener;
        return $this;
    }
}