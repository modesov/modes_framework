<?php

namespace Modes\Framework\Tests;

use Modes\Framework\Container\Container;
use Modes\Framework\Container\Exceptions\ContainerException;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class ContainerTest extends TestCase
{
    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function test_getting_service_from_container()
    {
        $container = new Container();

        $container->add('modes-class', ModesClass::class);

        $this->assertInstanceOf(ModesClass::class, $container->get('modes-class'));
    }

    /**
     * @throws ContainerExceptionInterface
     */
    public function test_getting_service_from_container_throws_ContainerException()
    {
        $container = new Container();

        $this->expectException(ContainerException::class);

        $container->add('no-class');

    }

    /**
     * @throws ContainerException
     */
    public function test_has_service()
    {
        $container = new Container();

        $container->add('modes-class', ModesClass::class);

        $this->assertTrue($container->has('modes-class'));

        $this->assertFalse($container->has('no-class'));
    }

    public function test_resolve()
    {
        $container = new Container();

        $container->add('modes-class', ModesClass::class);

        /** @var ModesClass $modes */
        $modes = $container->get('modes-class');

        $modesService = $modes->getModesService();

        $this->assertInstanceOf(ModesServices::class, $modesService);
        $this->assertInstanceOf(TelegramService::class, $modesService->getTelegramService());
        $this->assertInstanceOf(VkService::class, $modesService->getVkService());
    }
}