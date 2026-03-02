<?php

declare(strict_types=1);

namespace App\Core;

use App\Core\Request;
use App\Core\Exceptions\NotFoundException;

final class Router
{
    private array $routes = [];

    public function get(string $uri, callable $action): void
    {
        $this->addRoute('GET', $uri, $action);
    }

    public function post(string $uri, callable $action): void
    {
        $this->addRoute('POST', $uri, $action);
    }

    public function put(string $uri, callable $action): void
    {
        $this->addRoute('PUT', $uri, $action);
    }

    public function delete(string $uri, callable $action): void
    {
        $this->addRoute('DELETE', $uri, $action);
    }

    private function addRoute(string $method, string $uri, callable $action): void
    {
        // Convierte /api/clients/{id} → regex: /api/clients/([^/]+)
        $pattern = preg_replace('/\{[^}]+\}/', '([^/]+)', $uri);
        $pattern = '@^' . $pattern . '$@';

        // Extrae los nombres: {id} → 'id'
        preg_match_all('/\{([^}]+)\}/', $uri, $paramNames);

        $this->routes[$method][] = [
            'pattern'    => $pattern,
            'paramNames' => $paramNames[1], // ['id']
            'action'     => $action,
        ];
    }

    public function dispatch(Request $request): void
    {
        $method = $request->method();
        $uri    = $request->uri();

        $allowedMethods = [];

        foreach ($this->routes as $routeMethod => $routes) {

            foreach ($routes as $route) {

                if (!preg_match($route['pattern'], $uri, $matches)) {
                    continue;
                }

                // Si el patrón coincide pero el método no
                if ($routeMethod !== $method) {
                    $allowedMethods[] = $routeMethod;
                    continue;
                }

                array_shift($matches);

                $params = [];

                if (!empty($route['paramNames'])) {
                    $params = array_combine($route['paramNames'], $matches);
                }

                $route['action']($request, $params);
                return;
            }
        }

        if (!empty($allowedMethods)) {
            throw new \App\Core\Exceptions\MethodNotAllowedException(
                'Method not allowed',
                $allowedMethods
            );
        }

        throw new NotFoundException('Route not found');
    }
}
