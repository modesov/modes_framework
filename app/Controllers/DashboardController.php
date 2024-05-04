<?php

namespace App\Controllers;

use Modes\Framework\Controller\AbstractController;

class DashboardController extends AbstractController
{
    public function __invoke()
    {
        return $this->render('dashboard/index');
    }

}