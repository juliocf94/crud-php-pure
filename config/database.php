<?php

declare(strict_types=1);

return [
    'driver'   => 'mysql',
    'host'     => $_ENV['DB_HOST'] ?? 'localhost',
    'port'     => $_ENV['DB_PORT'] ?? '3306',
    'dbname'   => $_ENV['DB_NAME'] ?? '',
    'username' => $_ENV['DB_USER'] ?? '',
    'password' => $_ENV['DB_PASS'] ?? '',
    'charset'  => $_ENV['DB_CHARSET'] ?? 'utf8mb4',
];