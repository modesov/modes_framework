<?php

namespace App\Controllers;

use Modes\Framework\Http\Response;

class DocsController
{
    public function __invoke($category): Response
    {
        return new Response("<h1>Документация по Modes framework, категория $category</h1>");
    }
}