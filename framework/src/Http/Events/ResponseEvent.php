<?php

namespace Modes\Framework\Http\Events;

use Modes\Framework\Event\Event;
use Modes\Framework\Http\Request;
use Modes\Framework\Http\Response;

class ResponseEvent extends Event
{
    public function __construct(
        private Request $request,
        private Response $response
    )
    {
    }

    public function getRequest(): Request
    {
        return $this->request;
    }

    public function getResponse(): Response
    {
        return $this->response;
    }


}