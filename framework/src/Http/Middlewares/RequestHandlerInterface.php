<?php

namespace Modes\Framework\Http\Middlewares;

use Modes\Framework\Http\Request;
use Modes\Framework\Http\Response;

interface RequestHandlerInterface
{
    public function handle(Request $request): Response;
}