<?php

namespace Modes\Framework\Http\Responses;

use Modes\Framework\Controller\AbstractController;
use Modes\Framework\Http\Response;

class NotAllowedMethodResponse extends AbstractController
{
    public function index(string $message, array $allowedMethods): Response
    {
        $allowedMethods = implode(', ', $allowedMethods);
        $message = "{$message}. Allowed methods: {$allowedMethods}";
        return $this->render('not_allowed_method', ['message' => $message], new Response(statusCode: 405));
    }
}