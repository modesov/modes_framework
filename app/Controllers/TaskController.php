<?php

namespace App\Controllers;

use Modes\Framework\Http\Response;

class TaskController
{
    public function index(int $id): Response
    {
        return new Response(content: "<h1>Задача номер {$id}</h1>");
    }
}