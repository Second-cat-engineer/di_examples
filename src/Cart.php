<?php

namespace App;

use App\Calculator\SimpleCalculator;
use App\Storage\SimpleStorage;

class Cart
{
    /** @var CartItem[] */
    private array $items = [];

    private bool $loaded = false;

    public function getCost(): int
    {
        $this->loadItems();

        return (new SimpleCalculator())->getCost($this->items);
    }

    private function loadItems(): void
    {
        if ($this->loaded) {
            return;
        }

        $this->items = (new SimpleStorage('cart'))->load();
        $this->loaded = true;
    }
}