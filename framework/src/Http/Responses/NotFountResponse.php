<?php

namespace Modes\Framework\Http\Responses;

use App\Services\TelegramBotService;
use Modes\Framework\Controller\AbstractController;
use Modes\Framework\Http\Response;

class NotFountResponse extends AbstractController
{
    public function index(string $message): Response
    {
        return $this->render('error404', ['message' => $message], new Response(statusCode: 404));
    }
}