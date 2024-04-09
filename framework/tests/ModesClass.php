<?php

namespace Modes\Framework\Tests;

class ModesClass
{
    public function __construct(
        private readonly ModesServices $modesService,
    )
    {
    }

    public function getModesService(): ModesServices
    {
        return $this->modesService;
    }

}