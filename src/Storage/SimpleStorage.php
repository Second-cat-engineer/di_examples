<?php

namespace App\Storage;

use App\CartItem;

class SimpleStorage
{
    public function __construct(
        private readonly string $key
    ) {}

    /**
     * @return CartItem[]
     */
    public function load(): array
    {
        return isset($_SESSION[$this->key])
            ? unserialize($_SESSION[$this->key], ['allowed_classes' => [CartItem::class]])
            : [];
    }

    /**
     * @param CartItem[] $data
     */
    public function save(array $data): void
    {
        $_SESSION[$this->key] = serialize($data);
    }
}