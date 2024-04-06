<?php

namespace Modes\Framework\Http\Responses;

use Modes\Framework\Http\Response;

class NotAllowedMethodResponse
{
    public function index(string $allowedMethods): Response
    {
        return new Response(content: "Not allowed method. Allowed methods: {$allowedMethods}", statusCode: 405);
    }
}