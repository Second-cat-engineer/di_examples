<?php

namespace App;

use App\Calculator\CalculatorInterface;
use App\Storage\StorageInterface;
use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

#[Autoconfigure(
    public: true,
    autowire: true
)]
class Cart
{
    /** @var CartItem[] */
    private array $items = [];

    private bool $loaded = false;

    public function __construct(
        #[Autowire(service: CalculatorInterface::class)]
        private readonly CalculatorInterface $calculator,
        #[Autowire(service: StorageInterface::class)]
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