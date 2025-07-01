<?php

namespace App\Container;

use Psr\Container\ContainerInterface;
use ReflectionClass;

class Container implements ContainerInterface
{
    private array $definitions = [];

    public function set(string $id, string|callable $callback): void
    {
        $this->definitions[$id] = $callback;
    }

    public function get($id)
    {
        if (!$this->has($id)) {
            throw new ServiceNotFoundException('Undefined service: ' . $id);
        }

        $definition = $this->definitions[$id];

        $component = is_string($definition)
            ? $this->make($definition)
            : $definition($this);

        if (!$component) {
            throw new ContainerException('Undefined component ' . $id);
        }

        return $component;
    }

    public function has(string $id): bool
    {
        return isset($this->definitions[$id]);
    }

    private function make(string $definition): ?object
    {
        if (!class_exists($definition)) {
            return null;
        }

        $reflection = new ReflectionClass($definition);
        $arguments = [];
        if (($constructor = $reflection->getConstructor()) !== null) {
            foreach ($constructor->getParameters() as $param) {
                $paramClass = $param->getType();

                $arguments[] = $paramClass ? $this->get($paramClass->getName()) : null;
            }
        }

        return $reflection->newInstanceArgs($arguments);
    }
}