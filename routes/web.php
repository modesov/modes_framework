<?php

use App\Controllers\DashboardController;
use App\Controllers\DocsController;
use App\Controllers\GetUserController;
use App\Controllers\HomeController;
use App\Controllers\LoginController;
use App\Controllers\LogoutController;
use App\Controllers\RegisterUserController;
use Modes\Framework\Http\Middlewares\Authenticate;
use Modes\Framework\Http\Response;
use Modes\Framework\Routing\Route;

return [
    Route::get(uri: '/', handler: [HomeController::class, 'index']),

    Route::get(uri: '/docs', handler: function () {
        return new Response('<h1>Документация по Modes framework!</h1>');
    }),
    Route::get(uri: '/docs/{category}', handler: DocsController::class),

    Route::post(uri: '/users', handler: RegisterUserController::class),
    Route::get(uri: '/users/{id:\d+}', handler: GetUserController::class),

    Route::post(uri: '/login', handler: LoginController::class),
    Route::post(uri: '/logout', handler: LogoutController::class),

    Route::get(uri: '/dashboard', handler: DashboardController::class, middleware: [Authenticate::class]),
];
