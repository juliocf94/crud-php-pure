<?php

use App\Controllers\ClientController;
use App\Models\ClientRepository;

return function ($router, $db) {
    $controller = new ClientController(
        new ClientRepository($db)
    );
    $router->get('/api/clients',        fn()     => $controller->index());
};

/*$router->post('/api/clients',       fn()     => $controller->store());
$router->get('/api/clients/{id}',   fn($id)  => $controller->show($id));
$router->put('/api/clients/{id}',   fn($id)  => $controller->update($id));
$router->delete('/api/clients/{id}',fn($id)  => $controller->destroy($id));*/