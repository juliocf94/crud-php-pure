<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Response;
use App\Models\ClientRepository;

class ClientController
{
    public function __construct(private ClientRepository $repository) {}

    public function index(): void
    {
        $clients = $this->repository->findAll();

        Response::json([
            'status' => 'success',
            'data' => $clients
        ]);
    }
}