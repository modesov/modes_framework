<?php

use App\Controllers\HomeController;
use Modes\Framework\Http\Response;
use Modes\Framework\Routing\Route;

return [
    Route::get(uri: '/', handler: [HomeController::class, 'index']),
    Route::get(uri: '/docs/{name}', handler: function (string $name = 'добрый человек') {
        $name = urldecode($name);
        return new Response(content: "Привет, {$name}!");
    }),
];
