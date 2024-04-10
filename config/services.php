<?php

use League\Container\Argument\Literal\ArrayArgument;
use League\Container\Container;
use Modes\Framework\Http\Kernel;
use Modes\Framework\Routing\Router;
use Modes\Framework\Routing\RouterInterface;

// Application parameters
$routes = include BASE_PATH . '/routes/web.php';

// Application services
$container = new Container();
$container->add(id: RouterInterface::class, concrete: Router::class);
$container->extend(id: RouterInterface::class)
    ->addMethodCall(method: 'registerRoutes', args: [new ArrayArgument($routes)]);
$container->add(id: Kernel::class)->addArgument(arg: RouterInterface::class);

return $container;