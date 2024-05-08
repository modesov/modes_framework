<?php

namespace App\Listeners;

use Modes\Framework\Http\Events\ResponseEvent;

class ErrorListener
{
    public function __invoke(ResponseEvent $event): void
    {
        $response = $event->getResponse();

        if ($response->getStatusCode() >= 500) {
            $event->stopPropagation();
        }
    }
}