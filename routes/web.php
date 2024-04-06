<?php

use App\Controllers\HomeController;
use App\Controllers\TaskController;
use Modes\Framework\Routing\Route;

return [
    Route::get(uri: '/', handler: [HomeController::class, 'index']),
    Route::get(uri: '/tasks/{id:\d+}', handler: [TaskController::class, 'index']),
];
