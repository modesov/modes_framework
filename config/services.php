<?php

use App\Services\UserService;
use Doctrine\DBAL\Connection;
use League\Container\Argument\Literal\ArrayArgument;
use League\Container\Argument\Literal\StringArgument;
use League\Container\Container;
use League\Container\ReflectionContainer;
use Modes\Framework\Authentication\SessionAuthentication;
use Modes\Framework\Authentication\SessionAuthInterface;
use Modes\Framework\Authentication\UserServiceInterface;
use Modes\Framework\Console\Application;
use Modes\Framework\Console\Commands\MigrationCommand;
use Modes\Framework\Controller\AbstractController;
use Modes\Framework\Dbal\ConnectionFactory;
use Modes\Framework\Http\Kernel;
use Modes\Framework\Console\Kernel as ConsoleKernel;
<<<<<<< HEAD
=======
use Modes\Framework\Http\Middlewares\ExtractRouteInfo;
>>>>>>> 7e1ed4d (implement registration authentication)
use Modes\Framework\Http\Middlewares\RequestHandler;
use Modes\Framework\Http\Middlewares\RequestHandlerInterface;
use Modes\Framework\Http\Middlewares\RouterDispatch;
use Modes\Framework\Routing\Router;
use Modes\Framework\Routing\RouterInterface;
use Modes\Framework\Session\Session;
use Modes\Framework\Session\SessionInterface;
use Modes\Framework\Template\TwigFactory;
use Psr\Container\ContainerInterface;
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

$container->addShared(id: Connection::class, concrete: function () use ($container): Connection {
    return $container->get(ConnectionFactory::class)->create();
});

if ($sapi !== 'cli') {
    // Web
    $container->add(id: RouterInterface::class, concrete: Router::class);

    $container->add(id: RequestHandlerInterface::class, concrete: RequestHandler::class)
        ->addArgument($container);

<<<<<<< HEAD
    $container->add(id: Kernel::class)->addArguments(args: [RouterInterface::class, $container, RequestHandlerInterface::class]);
=======
    $container->add(id: Kernel::class)->addArguments(args: [$container, RequestHandlerInterface::class]);
>>>>>>> 7e1ed4d (implement registration authentication)

    $container->addShared(SessionInterface::class, Session::class);

    $viewsPath = BASE_PATH . '/views';
    $container->add('twig-factory', TwigFactory::class)
<<<<<<< HEAD
        ->addArguments([new StringArgument($viewsPath), SessionInterface::class]);
=======
        ->addArguments([
            new StringArgument($viewsPath),
            SessionInterface::class,
            SessionAuthInterface::class
        ]);
>>>>>>> 7e1ed4d (implement registration authentication)

    $container->addShared('twig', function () use ($container) {
        return $container->get(id: 'twig-factory')->create();
    });

    $container->inflector(type: AbstractController::class)
        ->invokeMethod(name: 'setContainer', args: [$container]);

    $container->add(id: RouterDispatch::class)
        ->addArguments(args: [
            RouterInterface::class,
            $container
        ]);
<<<<<<< HEAD
=======

    $container->add(id: UserServiceInterface::class, concrete: UserService::class)
        ->addArgument(arg: Connection::class);

    $container->add(id: SessionAuthInterface::class, concrete: SessionAuthentication::class)
        ->addArguments(args: [UserServiceInterface::class, SessionInterface::class]);

    $routes = include BASE_PATH . '/routes/web.php';
    $container->add(id: ExtractRouteInfo::class)
        ->addArgument(arg: new ArrayArgument($routes));
>>>>>>> 7e1ed4d (implement registration authentication)
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