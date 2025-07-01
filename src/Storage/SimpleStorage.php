<?php

namespace App\Storage;

use App\CartItem;

class SimpleStorage implements StorageInterface
{
    public function __construct(
        private readonly string $key
    ) {}

    /**
     * @inheritdoc
     */
    public function load(): array
    {
        return isset($_SESSION[$this->key])
            ? unserialize($_SESSION[$this->key], ['allowed_classes' => [CartItem::class]])
            : [];
    }

    /**
     * @inheritdoc
     */
    public function save(array $data): void
    {
        $_SESSION[$this->key] = serialize($data);
    }
}