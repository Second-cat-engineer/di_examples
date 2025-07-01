<?php

namespace App\Container;

use Psr\Container\ContainerInterface;

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
        return call_user_func($this->definitions[$id], $this);
    }

    public function has(string $id): bool
    {
        return isset($this->definitions[$id]);
    }
}