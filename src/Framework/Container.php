<?php

declare(strict_types=1);

namespace Framework;

use Framework\Exceptions\ContainerException;

class Container
{
    private array $definitions = [];

    private array $resolved = [];

    public function addDefinition(array $newDefinition): void
    {
        $this->definitions = [...$this->definitions, ...$newDefinition];
    }

    public function resolve(string $className)
    {
        $reflectionClass = new \ReflectionClass($className);

        if (!$reflectionClass->isInstantiable()) {
            throw new ContainerException("Class {$className} is not instantiable");
        }

        $constructor = $reflectionClass->getConstructor();

        if (empty($constructor)) {
            return new $className;
        }

        $constructorParameters = $constructor->getParameters();

        if (count($constructorParameters) === 0) {
            return new $className;
        }

        $dependencies = [];

        /**
         * @var \ReflectionParameter $constructorParameter
         */
        foreach ($constructorParameters as $constructorParameter) {
            $name = $constructorParameter->getName();
            $type = $constructorParameter->getType();

            if (!$type) {
                throw new ContainerException("Failed to resolve class {$className} because parameter {$name} is missing a type hint.");
            }

            if (!$type instanceof \ReflectionNamedType || $type->isBuiltin()) {
                throw new ContainerException("Failed to resolve class {$className} because of invalid parameter name.");
            }

            $dependencies[] = $this->get($type->getName());
        }

        return $reflectionClass->newInstanceArgs($dependencies);
    }

    public function get(string $id)
    {
        if (!array_key_exists($id, $this->definitions)) {
            throw new ContainerException("Class {$id} does not exist in container");
        }

        if (array_key_exists($id, $this->resolved)) {
            return $this->resolved[$id];
        }

        $factory = $this->definitions[$id];

        $dependency = $factory($this);

        $this->resolved[$id] = $dependency;

        return $dependency;
    }
}