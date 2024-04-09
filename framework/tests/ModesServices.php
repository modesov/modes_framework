<?php

namespace Modes\Framework\Tests;

class ModesServices
{
    public function __construct(
        private readonly TelegramService $telegramService,
        private readonly VkService       $vkService,
    )
    {
    }

    public function getTelegramService(): TelegramService
    {
        return $this->telegramService;
    }

    public function getVkService(): VkService
    {
        return $this->vkService;
    }

}