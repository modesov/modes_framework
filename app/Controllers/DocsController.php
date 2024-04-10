<?php

namespace App\Controllers;

use App\Services\TelegramBotService;
use Modes\Framework\Http\Response;

class DocsController
{
    public function __construct(
        private TelegramBotService $telegramBotService
    )
    {
    }

    public function __invoke($category): Response
    {
        return new Response("<h1>Документация по Modes framework, категория $category</h1> <a href='{$this->telegramBotService->getUrlToBotFrameworkDocs()}'>Подробнее в телеграм боте</a>");
    }
}