<?php

require __DIR__ . '/DB.php';
require __DIR__ . '/Logger.php';

try {
    $db = new DB();
    $products = $db->fetchAll('product');

    foreach ($products as $product) {
        echo $product['name'] . '<br>';
    }
} catch (RuntimeException $e) {
    echo 'Произошла ошибка: ' . $e->getMessage();
}
