<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\Calculator\CalculatorInterface;
use App\Calculator\SimpleCalculator;
use App\Cart;
use App\CartItem;
use App\Storage\SimpleStorage;
use App\Storage\StorageInterface;
use Symfony\Component\DependencyInjection\Reference;

$items = [
    new CartItem(1, 5, 100),
    new CartItem(2, 10, 50),
    new CartItem(3, 3, 13)
];
$_SESSION['cart'] = serialize($items);

//___________
$container = new Symfony\Component\DependencyInjection\ContainerBuilder();
$container->setParameter('cart_store', 'cart');

$container->register(StorageInterface::class, SimpleStorage::class)
    ->addArgument('%cart_store%');

$container->register(CalculatorInterface::class, SimpleCalculator::class);

$container->register(Cart::class, Cart::class)
    ->addArgument(new Reference(CalculatorInterface::class))
    ->addArgument(new Reference(StorageInterface::class))
    ->setShared(true);
//___________

try {
    $cart = $container->get(Cart::class);
    echo $cart->getCost() . PHP_EOL;

} catch (Exception $e) {
    echo $e->getMessage() . PHP_EOL;
}
