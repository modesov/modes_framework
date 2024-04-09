<?php

namespace Modes\Framework\Container;

use Modes\Framework\Container\Exceptions\ContainerException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class Container implements ContainerInterface
{
    private array $services = [];

    public function add(string $id, string|object $concrete = null): void
    {
        if (is_null($concrete)) {
            if (!class_exists($id)) {
                throw new ContainerException('Service "' . $id . '" does not exist.');
            }

            $concrete = $id;
        }

        $this->services[$id] = $concrete;
    }

    /**
     * @throws ContainerException
     * @throws NotFoundExceptionInterface
     * @throws \ReflectionException
     * @throws ContainerExceptionInterface
     */
    public function get(string $id)
    {
        if (!$this->has($id)) {
            if (!class_exists($id)) {
                throw new ContainerException('Service "' . $id . '" could not be resolved.');
            }

            $this->add($id);
        }

        return $this->resolve($this->services[$id]);
    }

    public function has(string $id): bool
    {
        return array_key_exists($id, $this->services);
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws \ReflectionException
     * @throws NotFoundExceptionInterface
     */
    private function resolve($class)
    {
        $reflection = new \ReflectionClass($class);

        $constructor = $reflection->getConstructor();

        if (is_null($constructor)) {
            return $reflection->newInstance();
        }

        $constructorParams = $constructor->getParameters();

        $dependencies = $this->getClassDependencies($constructorParams);

        return $reflection->newInstanceArgs($dependencies);
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws \ReflectionException
     */
    private function getClassDependencies(array $constructorParams): array
    {
        $dependencies = [];

        foreach ($constructorParams as $param) {
            $serviceType = $param->getType();

            $dependencies[] = $this->get($serviceType->getName());
        }

        return $dependencies;
    }
}