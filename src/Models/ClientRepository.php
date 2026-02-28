<?php

declare(strict_types=1);

namespace App\Models;

use PDO;

class ClientRepository
{
    public function __construct(private PDO $db) {}

    public function findAll(): array
    {
        $stmt = $this->db->query("
            SELECT id, first_name, last_name, email
            FROM clients
            WHERE deleted_at IS NULL
        ");

        return $stmt->fetchAll();
    }
}