<?php

// echo '<pre>' . print_r($headers, true) . '</pre>'; exit;

$host = 'localhost';
$db   = 'udt';
$user = 'root';
$pass = 'root';
$charset = 'utf8mb4';

if (($handle = fopen(__DIR__ . '/product.csv', 'r')) !== false) {
    $products = [];

    $headers = fgetcsv($handle, 0, ';');

    $replace = [
        'Название' => 'name',
        'Артикул' => 'art',
        'Цена' => 'price',
        'Количество' => 'quantity',
    ];
    
    $headers = array_map(function ($key) use ($replace) {
        $key = preg_replace('/[^[:alnum:]\p{L}\p{N}]+/u', '', $key);

        return $replace[$key] ?? $key;
    }, $headers);

    while (($row = fgetcsv($handle, 0, ';')) !== false) {
        $products[] = array_combine($headers, $row);
    }

    fclose($handle);
}

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);

    $sqlSelect = "SELECT * FROM product WHERE name = :name AND art = :art";
    $sqlInsert = "INSERT INTO product (name, art, price, quantity) VALUES (:name, :art, :price, :quantity)";
    $sqlUpdate = "UPDATE product SET price = :price, quantity = :quantity WHERE name = :name AND art = :art";

    $stmtSelect = $pdo->prepare($sqlSelect);
    $stmtInsert = $pdo->prepare($sqlInsert);
    $stmtUpdate = $pdo->prepare($sqlUpdate);

    $countAdded = 0;
    $countUpdated = 0;

    foreach ($products as $product) {
        $stmtSelect->execute([
            'name' => $product['name'],
            'art'  => $product['art'],
        ]);

        if ($stmtSelect->fetch()) {
            $stmtUpdate->execute($product);
            $countUpdated += $stmtUpdate->rowCount();
        } else {
            $stmtInsert->execute($product);
            $countAdded += $stmtInsert->rowCount();
        }
    }

} catch (PDOException $e) {
    echo "Ошибка: " . $e->getMessage();
}

echo 'Добавлено строк: ' . $countAdded . '<br>';
echo 'Обновлено строк: ' . $countUpdated . '<br>';