<?php

namespace App\Container;

use Psr\Container\ContainerInterface;
use ReflectionClass;

class Container implements ContainerInterface
{
    private array $definitions = [];
    private array $shared = [];

    public function set(string $id, string|callable $callback): void
    {
        $this->shared[$id] = null;
        $this->definitions[$id] = [
            'value' => $callback,
            'shared' => false,
        ];
    }

    public function setShared(string $id, string|callable $callback): void
    {
        $this->shared[$id] = null;
        $this->definitions[$id] = [
            'value' => $callback,
            'shared' => true,
        ];
    }

    public function get($id)
    {
        if (!$this->has($id)) {
            throw new ServiceNotFoundException('Undefined service: ' . $id);
        }

        if (isset($this->shared[$id])) {
            return $this->shared[$id];
        }

        if (array_key_exists($id, $this->definitions)) {
            $definition = $this->definitions[$id]['value'];
            $shared = $this->definitions[$id]['shared'];
        } else {
            $definition = $id;
            $shared = false;
        }

        $component = is_string($definition)
            ? $this->make($definition)
            : $definition($this);

        if (!$component) {
            throw new ContainerException('Undefined component ' . $id);
        }

        if ($shared) {
            $this->shared[$id] = $component;
        }

        return $component;
    }

    public function has(string $id): bool
    {
        if (isset($this->definitions[$id])) {
            return true;
        }
        if (class_exists($id)) {
            return true;
        }
        return false;
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