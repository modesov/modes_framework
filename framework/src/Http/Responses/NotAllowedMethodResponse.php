<?php

namespace Modes\Framework\Http\Responses;

use Modes\Framework\Http\Response;

class NotAllowedMethodResponse
{
    public function index(string $message, array $allowedMethods): Response
    {
        $allowedMethods = implode(', ', $allowedMethods);
        return new Response(content: "{$message}. Allowed methods: {$allowedMethods}", statusCode: 405);
    }
}