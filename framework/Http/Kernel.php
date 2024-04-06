<?php

namespace Modes\Framework\Http;

use Modes\Framework\Routing\RouterInterface;

class Kernel
{
    public function __construct(
        private readonly RouterInterface $router
    )
    {
    }

    public function handle(Request $request): Response
    {
        [$handler, $vars] = $this->router->dispatch($request);
        return call_user_func_array($handler, $vars);
    }
}