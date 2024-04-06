<?php

namespace App\Controllers;

use Modes\Framework\Http\Response;

class HomeController
{
    public function index():Response
    {
        return new Response(content: '<h1>Привет, мир! Я Modes фреймворк! А ты кто? Расскажи про себя...</h1>');
    }
}