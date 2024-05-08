<?php
define("BASE_PATH", dirname(__DIR__));
require BASE_PATH . '/vendor/autoload.php';

use Modes\Framework\Http\Kernel;
use Modes\Framework\Http\Request;

/** @var League\Container\Container $container */
$container = require BASE_PATH . '/config/services.php';

/** @var Modes\Framework\Event\ListenerProvider $listenerProvider */
$listenerProvider = $container->get(\Psr\EventDispatcher\ListenerProviderInterface::class);

$listenerProvider
    ->addListener(\Modes\Framework\Dbal\Events\EntityPersist::class, new \App\Listeners\SaveEntityListener())
    ->addListener(\Modes\Framework\Http\Events\ResponseEvent::class, new \App\Listeners\ErrorListener())
    ->addListener(\Modes\Framework\Http\Events\ResponseEvent::class, new \App\Listeners\ContentLengthListener());

$request = Request::createFromGlobals();

$kernel = $container->get(Kernel::class);

$response = $kernel->handle($request);

$response->send();

$kernel->terminate($request, $response);
