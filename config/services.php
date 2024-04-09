<?php

use League\Container\Container;
use Modes\Framework\Http\Kernel;
use Modes\Framework\Routing\Router;
use Modes\Framework\Routing\RouterInterface;

$container = new Container();
$container->add(id: RouterInterface::class, concrete: Router::class);
$container->add(id: Kernel::class)->addArgument(arg: RouterInterface::class);

return $container;