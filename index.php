<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\Calculator\SimpleCalculator;
use App\Cart;
use App\CartItem;
use App\Container\Container;
use App\Storage\SimpleStorage;

$items = [
    new CartItem(1, 5, 100),
    new CartItem(2, 10, 50),
    new CartItem(3, 3, 13)
];
$_SESSION['cart'] = serialize($items);

//___________
$container = new Container();
$container->set(SimpleStorage::class, fn () => new SimpleStorage('cart'));
$container->set(SimpleCalculator::class, fn () => new SimpleCalculator());
$container->set(Cart::class, fn (Container $container) => new Cart(
    $container->get(SimpleCalculator::class),
    $container->get(SimpleStorage::class))
);
//___________

try {
    $cart = $container->get(Cart::class);
    echo $cart->getCost() . PHP_EOL;

} catch (Exception $e) {
    echo $e->getMessage() . PHP_EOL;
}
