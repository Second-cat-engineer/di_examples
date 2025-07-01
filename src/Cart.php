<?php

namespace App;

use App\Calculator\CalculatorInterface;
use App\Storage\StorageInterface;

class Cart
{
    public string $description = 'default value';

    /** @var CartItem[] */
    private array $items = [];

    private bool $loaded = false;

    public function __construct(
        private readonly CalculatorInterface $calculator,
        private readonly StorageInterface $storage
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