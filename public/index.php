<?php

declare(strict_types=1);

define('BASE_PATH', dirname(__DIR__));

require BASE_PATH . '/vendor/autoload.php';

use Dotenv\Dotenv;
use App\Core\ExceptionHandler;
use App\Core\Database;
use App\Core\Router;
use App\Controllers\ClientController;
use App\Models\ClientRepository;
use App\Core\Middleware\MiddlewarePipeline;
use App\Core\Middleware\CorsMiddleware;
use App\Core\Middleware\JsonBodyMiddleware;
// use App\Core\Middleware\AuthMiddleware;

// 1. Cargar entorno
$dotenv = Dotenv::createImmutable(BASE_PATH);
$dotenv->load();

// 2. Registrar manejador de excepciones
ExceptionHandler::register();

// 3. Inicializar dependencias
$db = Database::getConnection();
$repository = new ClientRepository($db);
$controller = new ClientController($repository);

// 4. Registrar rutas
$router = new Router();

$router->get('/api/clients', fn() => $controller->index());

// 5. Configurar middleware y despachar UNA sola vez
$pipeline = new MiddlewarePipeline();

$pipeline
    ->add(new CorsMiddleware())
    ->add(new JsonBodyMiddleware());
    // ->add(new AuthMiddleware());

// 6. Procesar request a través del pipeline → dispatch
$pipeline->process(function () use ($router) {
    $router->dispatch(
        $_SERVER['REQUEST_METHOD'],
        $_SERVER['REQUEST_URI']
    );
});