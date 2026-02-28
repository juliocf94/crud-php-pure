<?php

declare(strict_types=1);

namespace App\Core;

final class Router
{
    private array $routes = [];

    public function get(string $uri, callable $action): void
    {
        $this->routes['GET'][$uri] = $action;
    }

    public function dispatch(string $method, string $uri): void
    {
        $uri = parse_url($uri, PHP_URL_PATH);

        if (isset($this->routes[$method][$uri])) {
            call_user_func($this->routes[$method][$uri]);
            return;
        }

        http_response_code(404);
        echo json_encode([
            'status' => 'error',
            'message' => 'Route not found'
        ]);
    }
}