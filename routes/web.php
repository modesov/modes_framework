<?php

use App\Controllers\DocsController;
use App\Controllers\HomeController;
use Modes\Framework\Http\Response;
use Modes\Framework\Routing\Route;

return [
    Route::get(uri: '/', handler: [HomeController::class, 'index']),
    Route::get(uri: '/docs', handler: function () {
        return new Response('<h1>Документация по Modes framework!</h1>');
    }),
    Route::get(uri: '/docs/{category}', handler: DocsController::class),
];
