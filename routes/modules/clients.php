<?php

use App\Controllers\ClientController;
use App\Models\ClientRepository;

return function (\App\Core\Router $router, \PDO $db): void {
    $controller = new ClientController(new ClientRepository($db));

    $router->get('/api/clients', function (\App\Core\Request $request) use ($controller) {
        $controller->index($request);
    });
};

/*$router->post('/api/clients',       fn()     => $controller->store());
$router->get('/api/clients/{id}',   fn($id)  => $controller->show($id));
$router->put('/api/clients/{id}',   fn($id)  => $controller->update($id));
$router->delete('/api/clients/{id}',fn($id)  => $controller->destroy($id));*/