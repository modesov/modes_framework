<?php

namespace Modes\Framework\Http\Responses;

use Modes\Framework\Http\Response;

class NotFountResponse
{
    public function index(string $message): Response
    {
        return new Response(content: $message, statusCode: 404);
    }

}