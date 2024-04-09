<?php
define("BASE_PATH", dirname(__DIR__));
require BASE_PATH . '/vendor/autoload.php';

use Modes\Framework\Http\Kernel;
use Modes\Framework\Http\Request;

/** @var League\Container\Container $container */
$container = require BASE_PATH . '/config/services.php';
$request = Request::createFromGlobals();

$kernel = $container->get(Kernel::class);
$kernel->handle($request)->send();