<?php

namespace App;

class CartItem
{
    public function __construct(
        public readonly int $id,
        private int $count,
        private int $price
    ) {}

    public function getCost(): int
    {
        return $this->price * $this->count;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getCount(): int
    {
        return $this->count;
    }

    public function setCount(int $count): void
    {
        $this->count = $count;
    }

    public function getPrice(): int
    {
        return $this->price;
    }

    public function setPrice(int $price): void
    {
        $this->price = $price;
    }
}