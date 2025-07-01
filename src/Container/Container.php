<?php

namespace App\Container;

use Psr\Container\ContainerInterface;
use ReflectionClass;

class Container implements ContainerInterface
{
    private array $definitions = [];
    private array $parameters = [];
    private array $shared = [];

    public function setParameter(string $name, mixed $value): void
    {
        $this->parameters[$name] = $value;
    }

    public function getParameter(string $name): mixed
    {
        if (!array_key_exists($name, $this->parameters)) {
            throw new \InvalidArgumentException("Parameter '$name' not found.");
        }
        return $this->parameters[$name];
    }

    public function set(string $id, string|callable $callback, array $arguments = []): void
    {
        $this->shared[$id] = null;
        $this->definitions[$id] = [
            'value' => $callback,
            'shared' => false,
            'arguments' => $arguments,
        ];
    }

    public function setShared(string $id, string|callable $callback, array $arguments = []): void
    {
        $this->shared[$id] = null;
        $this->definitions[$id] = [
            'value' => $callback,
            'shared' => true,
            'arguments' => $arguments,
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
            $arguments = $this->definitions[$id]['arguments'];
        } else {
            $definition = $id;
            $shared = false;
            $arguments = [];
        }

        $component = is_string($definition)
            ? $this->make($definition, $arguments)
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

    private function make(string $definition, array $forcedArguments = []): ?object
    {
        if (!class_exists($definition)) {
            return null;
        }

        $reflection = new ReflectionClass($definition);
        $arguments = [];
        if (($constructor = $reflection->getConstructor()) !== null) {
            foreach ($constructor->getParameters() as $index => $param) {
                if (array_key_exists($index, $forcedArguments)) {
                    $arg = $forcedArguments[$index];

                    // Поддержка %param%
                    if (is_string($arg) && preg_match('/^%(.+)%$/', $arg, $matches)) {
                        $arg = $this->getParameter($matches[1]);
                    }

                    $arguments[] = $arg;
                    continue;
                }

                $paramClass = $param->getType();

                $arguments[] = $paramClass ? $this->get($paramClass->getName()) : null;
            }
        }

        return $reflection->newInstanceArgs($arguments);
    }
}