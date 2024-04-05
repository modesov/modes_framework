<?php

use Modes\Framework\Http\Response;
use Modes\Framework\Routing\Route;

return [
   Route::get(uri: '/',  handler: function () {
       return new Response(content: '<h1>Привет мир! Я Modes фреймворк! А ты кто?</h1>');
   })
];
