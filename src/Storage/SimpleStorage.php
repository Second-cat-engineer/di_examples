<?php

namespace App\Storage;

use App\CartItem;
use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

#[Autoconfigure]
class SimpleStorage implements StorageInterface
{
    public function __construct(
        #[Autowire('%cart_store%')]
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