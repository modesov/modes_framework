<?php

namespace App\Controllers;

use App\Services\TelegramBotService;
use Modes\Framework\Controller\AbstractController;
use Modes\Framework\Http\Response;

class HomeController extends AbstractController
{
    public function __construct(
        private TelegramBotService $telegramBotService
    )
    {
    }

    public function index(): Response
    {
        return $this->render('home', ['url' => $this->telegramBotService->getUrlToBotFrameworkDocs()]);
    }
}