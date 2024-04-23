<?php

use Doctrine\DBAL\Connection;
use League\Container\Argument\Literal\ArrayArgument;
use League\Container\Argument\Literal\StringArgument;
use League\Container\Container;
use League\Container\ReflectionContainer;
use Modes\Framework\Console\Application;
use Modes\Framework\Console\Commands\MigrationCommand;
use Modes\Framework\Controller\AbstractController;
use Modes\Framework\Dbal\ConnectionFactory;
use Modes\Framework\Http\Kernel;
use Modes\Framework\Console\Kernel as ConsoleKernel;
use Modes\Framework\Routing\Router;
use Modes\Framework\Routing\RouterInterface;
use Symfony\Component\Dotenv\Dotenv;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

$dotenv = new Dotenv();
$dotenv->load(BASE_PATH . '/.env');
$sapi = php_sapi_name();

// Application parameters
$appEnv = $_ENV['APP_ENV'] ?? 'local';
$databaseConfiguration = [
    'host' => $_ENV['DB_HOST'] ?? 'localhost',
    'port' => $_ENV['DB_PORT'] ?? '5432',
    'user' => $_ENV['DB_USER'] ?? 'postgres',
    'password' => $_ENV['DB_PASSWORD'] ?? 'postgres',
    'dbname' => $_ENV['DB_NAME'] ?? 'postgres',
    'driver' => $_ENV['DB_DRIVER'] ?? 'pdo_pgsql',
];


// Application services
$container = new Container();
$container->delegate(new ReflectionContainer(cacheResolutions: true));
$container->add(id: 'APP_ENV', concrete: new StringArgument($appEnv));
$container->add(id: ConnectionFactory::class)->addArgument(new ArrayArgument($databaseConfiguration));

$container->addShared(id: Connection::class, concrete: function() use ($container): Connection {
    return $container->get(ConnectionFactory::class)->create();
});

if ($sapi !== 'cli') {
    // Web
    $routes = include BASE_PATH . '/routes/web.php';
    $container->add(id: RouterInterface::class, concrete: Router::class);
    $container->extend(id: RouterInterface::class)
        ->addMethodCall(method: 'registerRoutes', args: [new ArrayArgument($routes)]);

    $container->add(id: Kernel::class)->addArguments(args: [RouterInterface::class, $container]);

    $viewsPath = BASE_PATH . '/views';
    $container->addShared(id: 'twig-loader', concrete: FilesystemLoader::class)
        ->addArgument(new StringArgument($viewsPath));

    $container->addShared(id: 'twig', concrete: Environment::class)
        ->addArgument(arg: 'twig-loader');

    $container->inflector(type: AbstractController::class)
        ->invokeMethod(name: 'setContainer', args: [$container]);
} else {
    // Console
    $container->add(id: 'framework-command-namespace', concrete: new StringArgument('Modes\\Framework\\Console\\Commands\\'));

    $container->add(id: Application::class)->addArgument($container);

    $container->add(id: ConsoleKernel::class)->addArguments(args: [$container, Application::class]);

    $container->add(id: 'mc:migrate', concrete: MigrationCommand::class)
        ->addArgument(arg: Connection::class)
        ->addArgument(arg: new StringArgument(BASE_PATH . '/database/migrations'));
}

return $container;