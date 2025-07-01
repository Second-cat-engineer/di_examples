<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\Calculator\CalculatorInterface;
use App\Calculator\SimpleCalculator;
use App\Cart;
use App\CartItem;
use App\Storage\SimpleStorage;
use App\Storage\StorageInterface;
use Yiisoft\Definitions\Reference;
use Yiisoft\Di\Container;
use Yiisoft\Di\ContainerConfig;

$items = [
    new CartItem(1, 5, 100),
    new CartItem(2, 10, 50),
    new CartItem(3, 34, 13)
];
$_SESSION['cart'] = serialize($items);

//___________
$config = ContainerConfig::create()
    ->withDefinitions([
        StorageInterface::class => [
            'class' => SimpleStorage::class,
            '__construct()' => ['cart'],
        ],
        CalculatorInterface::class => SimpleCalculator::class,
        Cart::class => [
            '__construct()' => [
                Reference::to(CalculatorInterface::class),
                Reference::to(StorageInterface::class),
            ],
        ],
    ]);

$container = new Container($config);
//___________

try {
    $cart = $container->get(Cart::class);
    echo $cart->getCost() . PHP_EOL;

} catch (Exception $e) {
    echo $e->getMessage() . PHP_EOL;
}
