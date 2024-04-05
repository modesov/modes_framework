<?php
define("BASE_PATH", dirname(__DIR__));
require BASE_PATH . '/vendor/autoload.php';

use Modes\Framework\Http\Kernel;
use Modes\Framework\Http\Request;

$request = Request::createFromGlobals();
$kernel = new Kernel();
$kernel->handle($request)->send();