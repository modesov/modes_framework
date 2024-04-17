<?php

use Doctrine\DBAL\Connection;
use League\Container\Argument\Literal\StringArgument;
use League\Container\Container;
use League\Container\ReflectionContainer;
use Modes\Framework\Console\Application;
use Modes\Framework\Dbal\ConnectionFactory;
use Modes\Framework\Console\Kernel;
use Modes\Framework\Routing\RouterInterface;
use Symfony\Component\Dotenv\Dotenv;

$dotenv = new Dotenv();
$dotenv->load(BASE_PATH . '/.env');

// Application parameters
$appEnv = $_ENV['APP_ENV'] ?? 'local';
$databaseUrl = $_ENV['DB_URL'] ?? 'pdo-sqlite:///db.sqlite';


// Application services
$container = new Container();

$container->delegate(new ReflectionContainer(cacheResolutions: true));

$container->add(id: 'APP_ENV', concrete: new StringArgument($appEnv));
$container->add(id: 'framework-command-namespace', concrete: new StringArgument('Modes\\Framework\\Console\\Commands\\'));
$container->add(id: ConnectionFactory::class)->addArgument(new StringArgument($databaseUrl));
$container->addShared(id: Connection::class, concrete: function() use ($container): Connection {
    return $container->get(ConnectionFactory::class)->create();
});
$container->add(id: Application::class)->addArgument($container);
$container->add(id: Kernel::class)->addArguments(args: [$container, Application::class]);

return $container;