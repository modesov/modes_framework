<?php

namespace App\Controllers;

use Modes\Framework\Http\Response;

class HomeController
{
    public function index():Response
    {
        return new Response(content: '<h1>Описание Modes framework</h1>');
    }
}