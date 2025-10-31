<?php

class DB
{
    private $pdo;

    private array $config = [
        'host' => 'localhost',
        'db'   => 'udt',
        'user' => 'root',
        'pass' => 'root',
        'charset' => 'utf8mb4',
        'log_file' => 'db.log',
    ];

    private array $allowedTables = [
        'product',
    ];

    public function __construct(array $config = [])
    {
        $this->config = array_merge($this->config, $config);

        $dsn = sprintf(
            'mysql:host=%s;dbname=%s;charset=%s',
            $this->config['host'],
            $this->config['db'],
            $this->config['charset']
        );

        try {
            $this->pdo = new PDO($dsn, $this->config['user'], $this->config['pass'], [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ]);

            $this->log("Подключение к БД успешно");
        } catch (PDOException $e) {
            $this->log("Ошибка подключения к БД: " . $e->getMessage());

            throw new RuntimeException("Ошибка подключения к базе данных");
        }
    }

    public function fetchAll(string $table): array
    {
        if (! in_array($table, $this->allowedTables, true)) {
            $this->log("Неизвестная таблица: $table");

            throw new InvalidArgumentException("Неизвестная таблица: $table");
        }

        try {
            $stmt = $this->pdo->query("SELECT * FROM $table");
            $data = $stmt->fetchAll();
            $this->log("Получено " . count($data) . " записей из таблицы product");

            return $data;
        } catch (PDOException $e) {
            $this->log("Ошибка выполнения запроса: " . $e->getMessage());
        }

        return [];
    }

    private function log(string $message): void
    {
        $time = date('Y-m-d H:i:s');

        file_put_contents($this->config['log_file'], "[$time] $message" . PHP_EOL, FILE_APPEND);
    }
}

try {
    $db = new DB();
    $products = $db->fetchAll('product');

    foreach ($products as $product) {
        echo $product['name'] . '<br>';
    }
} catch (RuntimeException $e) {
    echo 'Произошла ошибка: ' . $e->getMessage();
}
