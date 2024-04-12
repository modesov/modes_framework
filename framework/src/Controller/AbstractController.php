<?php

namespace Modes\Framework\Controller;

use Modes\Framework\Http\Response;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

abstract class AbstractController
{
    protected ?ContainerInterface $container = null;

    public function setContainer(ContainerInterface $container): void
    {
        $this->container = $container;
    }


    public function render(string $view, array $params = [], Response $response = null): Response
    {
        $content = $this->getTwig()->render("$view.html.twig", $params);

        $response ??= new Response();

        $response->setContent($content);

        return $response;
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    private function getTwig(): Environment
    {
        return $this->container->get('twig');
    }
}