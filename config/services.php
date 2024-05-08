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
use Modes\Framework\Dbal\EntityService;
use Modes\Framework\Event\EventDispatcher;
use Modes\Framework\Event\ListenerProvider;
use Modes\Framework\Http\Kernel;
use Modes\Framework\Console\Kernel as ConsoleKernel;
use Modes\Framework\Http\Middlewares\ExtractRouteInfo;
use Modes\Framework\Http\Middlewares\RequestHandler;
use Modes\Framework\Http\Middlewares\RequestHandlerInterface;
use Modes\Framework\Http\Middlewares\RouterDispatch;
use Modes\Framework\Routing\Router;
use Modes\Framework\Routing\RouterInterface;
use Modes\Framework\Session\Session;
use Modes\Framework\Session\SessionInterface;
use Modes\Framework\Template\TwigFactory;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Dotenv\Dotenv;


$basePath = dirname(__DIR__);
$dotenv = new Dotenv();
$dotenv->load($basePath . '/.env');
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
$container->add('base-path', $basePath);

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

    $container->addShared(id: ListenerProvider::class);

    $container->addShared(id: EventDispatcherInterface::class, concrete: EventDispatcher::class)
        ->addArgument(arg: ListenerProvider::class);

    $container->add(id: Kernel::class)
        ->addArguments(args: [
            $container,
            RequestHandlerInterface::class,
            EventDispatcherInterface::class,
        ]);

    $container->addShared(SessionInterface::class, Session::class);

    $viewsPath = $basePath . '/views';
    $container->add('twig-factory', TwigFactory::class)
        ->addArguments([
            new StringArgument($viewsPath),
            SessionInterface::class,
            SessionAuthInterface::class
        ]);

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

    $container->add(id: UserServiceInterface::class, concrete: UserService::class)
        ->addArgument(arg: EntityService::class);

    $container->add(id: SessionAuthInterface::class, concrete: SessionAuthentication::class)
        ->addArguments(args: [UserServiceInterface::class, SessionInterface::class]);

    $routes = include $basePath . '/routes/web.php';
    $container->add(id: ExtractRouteInfo::class)
        ->addArgument(arg: new ArrayArgument($routes));
} else {
    // Console
    $container->add(id: 'framework-command-namespace', concrete: new StringArgument('Modes\\Framework\\Console\\Commands\\'));

    $container->add(id: Application::class)->addArgument($container);

    $container->add(id: ConsoleKernel::class)->addArguments(args: [$container, Application::class]);

    $container->add(id: 'mc:migrate', concrete: MigrationCommand::class)
        ->addArgument(arg: Connection::class)
        ->addArgument(arg: new StringArgument($basePath . '/database/migrations'));
}

return $container;