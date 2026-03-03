<?php

use Dotenv\Dotenv;
use App\Core\Application;
use App\Core\Middleware\CorsMiddleware;
use App\Core\Database;
use App\Core\Middleware\JsonBodyMiddleware;

$dotenv = Dotenv::createImmutable(BASE_PATH);
$dotenv->load();

// Constants
const ROUTES_PATH = BASE_PATH . '/routes/modules/';

$db = Database::getConnection();
$app = new Application($db);

// Middleware global
$app->addMiddleware(new CorsMiddleware());
$app->addMiddleware(new JsonBodyMiddleware());

// Cargar archivos de rutas
$app->loadRoutes([
    ROUTES_PATH . 'clients.php',
]);

return $app;
