<?php

namespace Modes\Framework\Event;

use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\EventDispatcher\StoppableEventInterface;

readonly class EventDispatcher implements EventDispatcherInterface
{
    public function __construct(private ListenerProvider $provider)
    {
    }

    public function dispatch(object $event): void
    {
        foreach ($this->provider->getListenersForEvent($event) as $listener) {
            if ($event instanceof StoppableEventInterface && $event->isPropagationStopped()) {
                break;
            }

            $listener($event);
        }
    }
}