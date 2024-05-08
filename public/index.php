<?php
define("BASE_PATH", dirname(__DIR__));
require BASE_PATH . '/vendor/autoload.php';

use Modes\Framework\Http\Kernel;
use Modes\Framework\Http\Request;

/** @var League\Container\Container $container */
$container = require BASE_PATH . '/config/services.php';

require_once BASE_PATH . '/bootstrap/bootstrap.php';

$request = Request::createFromGlobals();

$kernel = $container->get(Kernel::class);

$response = $kernel->handle($request);

$response->send();

$kernel->terminate($request, $response);
