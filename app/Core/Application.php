<?php

namespace App\Core;

use App\Core\ExceptionHandler;
use App\Core\Router;
use App\Core\Middleware\MiddlewarePipeline;
use App\Core\Request;

class Application
{
    private Router $router;
    private MiddlewarePipeline $pipeline;

    public function __construct(private \PDO $db)
    {
        ExceptionHandler::register();
        $this->router   = new Router();
        $this->pipeline = new MiddlewarePipeline();
    }

    public function addMiddleware(mixed $middleware): self
    {
        $this->pipeline->add($middleware);
        return $this;
    }

    public function loadRoutes(string $directory): void
    {
        foreach (glob($directory . '/*.php') as $file) {

            $routes = require $file;

            if (is_callable($routes)) {
                $routes($this->router, $this->db);
            }
        }
    }

    public function run(): void
    {
        $request = Request::capture();

        $this->pipeline->process(function () use ($request) {
            $this->router->dispatch($request);
        });
    }
}