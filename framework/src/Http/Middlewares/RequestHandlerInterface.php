<?php

namespace Modes\Framework\Http\Middlewares;

use Modes\Framework\Http\Request;
use Modes\Framework\Http\Response;

interface RequestHandlerInterface
{
<<<<<<< HEAD
=======
    public function injectionMiddlewares(array $middlewares): void;
>>>>>>> 7e1ed4d (implement registration authentication)
    public function handle(Request $request): Response;
}