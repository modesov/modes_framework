<?php

use League\Container\Argument\Literal\ArrayArgument;
use League\Container\Argument\Literal\StringArgument;
use League\Container\Container;
use League\Container\ReflectionContainer;
use Modes\Framework\Http\Kernel;
use Modes\Framework\Routing\Router;
use Modes\Framework\Routing\RouterInterface;
use Symfony\Component\Dotenv\Dotenv;

$dotenv = new Dotenv();
$dotenv->load(BASE_PATH.'/.env');

// Application parameters
$routes = include BASE_PATH . '/routes/web.php';

// Application services
$container = new Container();

$container->delegate(new ReflectionContainer(cacheResolutions: true));

$appEnv = $_ENV['APP_ENV'] ?? 'local';
$container->add(id: 'APP_ENV', concrete: new StringArgument($appEnv));

$container->add(id: RouterInterface::class, concrete: Router::class);

$container->extend(id: RouterInterface::class)
    ->addMethodCall(method: 'registerRoutes', args: [new ArrayArgument($routes)]);

$container->add(id: Kernel::class)->addArguments(args: [RouterInterface::class, $container]);

return $container;