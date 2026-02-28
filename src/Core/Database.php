<?php

declare(strict_types=1);

namespace App\Core;

use PDO;
use PDOException;
use Config\AppConfig;

final class Database
{
    private static ?PDO $connection = null;

    private function __construct() {}

    public static function getConnection(): PDO
    {
        if (self::$connection === null) {
            $config = AppConfig::database();

            $dsn = sprintf(
                '%s:host=%s;port=%s;dbname=%s;charset=%s',
                $config['driver'],
                $config['host'],
                $config['port'],
                $config['dbname'],
                $config['charset']
            );

            try {
                self::$connection = new PDO(
                    $dsn,
                    $config['username'],
                    $config['password'],
                    [
                        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                        PDO::ATTR_EMULATE_PREPARES   => false,
                    ]
                );
            } catch (PDOException $e) {
                throw new PDOException(
                    'Database connection failed: ' . $e->getMessage(),
                    (int) $e->getCode()
                );
            }
        }

        return self::$connection;
    }
}
