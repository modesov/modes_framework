<?php

namespace App\Providers;

use App\Listeners\ContentLengthListener;
use App\Listeners\ErrorListener;
use App\Listeners\SaveEntityListener;
use Modes\Framework\Dbal\Events\EntityPersist;
use Modes\Framework\Event\ListenerProvider;
use Modes\Framework\Http\Events\ResponseEvent;
use Modes\Framework\Providers\ServiceProviderInterface;

class EventServiceProvider implements ServiceProviderInterface
{
    private array $listeners = [
        ResponseEvent::class => [
            ErrorListener::class,
            ContentLengthListener::class
        ],
        EntityPersist::class => [
            SaveEntityListener::class
        ]
    ];

    public function __construct(
        private ListenerProvider $provider
    )
    {
    }

    public function register(): void
    {
        foreach ($this->listeners as $event => $eventListeners) {
            foreach (array_unique($eventListeners) as $listener) {
                $this->provider->addListener($event, new $listener);
             }
        }
    }
}