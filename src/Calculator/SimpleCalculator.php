<?php

namespace App\Calculator;

use App\CartItem;

class SimpleCalculator implements CalculatorInterface
{
    /**
     * @inheritdoc
     */
    public function getCost(array $items): int
    {
        $cost = 0;
        foreach ($items as $item) {
            $cost += $item->getCost();
        }

        return $cost;
    }
}