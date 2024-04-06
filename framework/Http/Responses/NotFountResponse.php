<?php

namespace Modes\Framework\Http\Responses;

use Modes\Framework\Http\Response;

class NotFountResponse
{
    public function index(): Response
    {
        return new Response(content: '404 Not Found', statusCode: 404);
    }

}