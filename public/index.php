<?php
define("BASE_PATH", dirname(__DIR__));
require BASE_PATH . '/vendor/autoload.php';

use Modes\Framework\Http\Kernel;
use Modes\Framework\Http\Request;
use Modes\Framework\Routing\Router;

$request = Request::createFromGlobals();
$router = new Router();
$kernel = new Kernel($router);
$kernel->handle($request)->send();