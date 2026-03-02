<?php

namespace App\Core;

use App\Core\ExceptionHandler;
use App\Core\Database;
use App\Core\Router;
use App\Core\Middleware\MiddlewarePipeline;

class Application
{
    private Router $router;
    private MiddlewarePipeline $pipeline;
    private \PDO $db;

    public function __construct()
    {
        ExceptionHandler::register();
        $this->db       = Database::getConnection();
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
        $router = $this->router;
        $db     = $this->db;

        foreach ($files as $file) {
            require $file;
        }
    }

    public function run(): void
    {
        $this->pipeline->process(function () {
            $this->router->dispatch(
                $_SERVER['REQUEST_METHOD'],
                $_SERVER['REQUEST_URI']
            );
        });
    }
}