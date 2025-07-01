<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\Calculator\CalculatorInterface;
use App\Calculator\SimpleCalculator;
use App\Cart;
use App\CartItem;
use App\Storage\SimpleStorage;
use App\Storage\StorageInterface;
use Illuminate\Container\Container;

$items = [
    new CartItem(1, 5, 100),
    new CartItem(2, 10, 50),
    new CartItem(3, 3, 13)
];
$_SESSION['cart'] = serialize($items);

//___________
$container = new Container();

$container->bind(StorageInterface::class, fn () => new SimpleStorage('cart'));
$container->bind(CalculatorInterface::class, fn () => new SimpleCalculator());
$container->singleton(Cart::class, fn ($container) => new Cart(
    $container->make(CalculatorInterface::class),
    $container->make(StorageInterface::class)
));
//___________

try {
    $cart = $container->get(Cart::class);
    echo $cart->getCost() . PHP_EOL;

} catch (Exception $e) {
    echo $e->getMessage() . PHP_EOL;
}
