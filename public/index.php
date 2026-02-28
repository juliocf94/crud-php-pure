<?php

declare(strict_types=1);

define('BASE_PATH', dirname(__DIR__));

require BASE_PATH . '/vendor/autoload.php';

use Dotenv\Dotenv;
use App\Core\Database;
use App\Core\Router;
use App\Controllers\ClientController;
use App\Models\ClientRepository;

// Load env
$dotenv = Dotenv::createImmutable(BASE_PATH);
$dotenv->load();

// Initialize dependencies (Manual DI)
$db = Database::getConnection();
$repository = new ClientRepository($db);
$controller = new ClientController($repository);

// Router
$router = new Router();

$router->get('/api/clients', function () use ($controller) {
    $controller->index();
});

$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);