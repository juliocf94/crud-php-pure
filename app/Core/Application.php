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
        $this->db       = $db;
        $this->router   = new Router();
        $this->pipeline = new MiddlewarePipeline();
    }

    public function addMiddleware(mixed $middleware): self
    {
        $this->pipeline->add($middleware);
        return $this;
    }

    public function loadRoutes(array $files): void
    {
        foreach ($files as $file) {
            $routes = require $file;
            $routes($this->router, $this->db);
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
