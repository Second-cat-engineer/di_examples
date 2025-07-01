<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\Cart;
use App\CartItem;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

$items = [
    new CartItem(1, 5, 100),
    new CartItem(2, 10, 50),
    new CartItem(3, 3, 13)
];
$_SESSION['cart'] = serialize($items);

//___________
$container = new ContainerBuilder();
$loader = new YamlFileLoader(
    $container,
    new Symfony\Component\Config\FileLocator(__DIR__)
);
$loader->load('services.yml');

// Компиляция контейнера (обязательно после загрузки всех конфигов)
$container->compile();
//___________

try {
    $cart = $container->get(Cart::class);
    echo $cart->getCost() . PHP_EOL;

} catch (Exception $e) {
    echo $e->getMessage() . PHP_EOL;
}
