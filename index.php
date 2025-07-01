<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\Calculator\CalculatorInterface;
use App\Calculator\SimpleCalculator;
use App\Cart;
use App\CartItem;
use App\Container\Container;
use App\Storage\SimpleStorage;
use App\Storage\StorageInterface;

$items = [
    new CartItem(1, 5, 100),
    new CartItem(2, 10, 50),
    new CartItem(3, 3, 13)
];
$_SESSION['cart'] = serialize($items);

//___________
$container = new Container();
$container->set(StorageInterface::class, fn () => new SimpleStorage('cart'));
$container->set(CalculatorInterface::class, SimpleCalculator::class);
$container->setShared(Cart::class, Cart::class);
//___________

try {
    $cart = $container->get(Cart::class);

    echo $cart->description . PHP_EOL;
    $cart->description = 'this object is singleton';

    $cart = $container->get(Cart::class);
    echo $cart->description . PHP_EOL;

    echo $cart->getCost() . PHP_EOL;

} catch (Exception $e) {
    echo $e->getMessage() . PHP_EOL;
}
