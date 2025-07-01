<?php

namespace App;

use App\Calculator\SimpleCalculator;
use App\Storage\SimpleStorage;

class Cart
{
    /** @var CartItem[] */
    private array $items = [];

    private bool $loaded = false;

    public function __construct(
        private readonly SimpleCalculator $calculator,
        private readonly SimpleStorage $storage
    )
    {}


    public function getCost(): int
    {
        $this->loadItems();

        return $this->calculator->getCost($this->items);
    }

    private function loadItems(): void
    {
        if ($this->loaded) {
            return;
        }

        $this->items = $this->storage->load();
        $this->loaded = true;
    }
}