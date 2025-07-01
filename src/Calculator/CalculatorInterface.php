<?php

namespace App\Calculator;

use App\CartItem;

interface CalculatorInterface
{
    /**
     * @param CartItem[] $items
     * @return int
     */
    public function getCost(array $items): int;
}