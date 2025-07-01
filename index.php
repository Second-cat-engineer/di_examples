<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\Calculator\SimpleCalculator;
use App\Cart;
use App\CartItem;
use App\Storage\SimpleStorage;

$items = [
    new CartItem(1, 5, 100),
    new CartItem(2, 10, 50),
    new CartItem(3, 3, 13)
];
$_SESSION['cart'] = serialize($items);

try {
    $cart = new Cart(new SimpleCalculator(), new SimpleStorage('cart'));
    echo $cart->getCost() . PHP_EOL;

} catch (Exception $e) {
    echo $e->getMessage() . PHP_EOL;
}
