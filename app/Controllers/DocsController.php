<?php

namespace App\Controllers;

use App\Services\TelegramBotService;
use Modes\Framework\Controller\AbstractController;
use Modes\Framework\Http\Response;

class DocsController extends AbstractController
{
    public function __construct(
        private TelegramBotService $telegramBotService
    )
    {
    }

    public function __invoke($category): Response
    {
        return $this->render('docs_category', ['url' => $this->telegramBotService->getUrlToBotFrameworkDocs(), 'category' => $category]);
    }
}