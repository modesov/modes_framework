<?php

namespace Modes\Framework\Template;

<<<<<<< HEAD
=======
use Modes\Framework\Authentication\SessionAuthInterface;
>>>>>>> 7e1ed4d (implement registration authentication)
use Modes\Framework\Session\SessionInterface;
use Twig\Environment;
use Twig\Extension\DebugExtension;
use Twig\Loader\FilesystemLoader;
use Twig\TwigFunction;

class TwigFactory
{
    public function __construct(
        private string $viewsPath,
<<<<<<< HEAD
        private SessionInterface $session
=======
        private SessionInterface $session,
        private SessionAuthInterface $auth
>>>>>>> 7e1ed4d (implement registration authentication)
    )
    {
    }

    public function create(): Environment
    {
        $loader = new FilesystemLoader($this->viewsPath);

        $twig = new Environment($loader, [
            'debug' => true,
            'cache' => false,
        ]);

        $twig->addExtension(new DebugExtension());
        $twig->addFunction(new TwigFunction(name: 'session', callable: [$this, 'getSession']));
<<<<<<< HEAD
=======
        $twig->addFunction(new TwigFunction(name: 'auth', callable: [$this, 'getAuth']));
>>>>>>> 7e1ed4d (implement registration authentication)

        return $twig;
    }

    public function getSession(): SessionInterface
    {
        return $this->session;
    }

<<<<<<< HEAD
=======
    public function getAuth(): SessionAuthInterface
    {
        return $this->auth;
    }

>>>>>>> 7e1ed4d (implement registration authentication)
}