<?php

namespace App\Storage;

use App\CartItem;

interface StorageInterface
{
    /**
     * @return CartItem[]
     */
    public function load(): array;

    /**
     * @param CartItem[] $data
     */
    public function save(array $data): void;
}