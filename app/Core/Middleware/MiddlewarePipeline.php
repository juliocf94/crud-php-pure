<?php

declare(strict_types=1);

namespace App\Core\Middleware;

final class MiddlewarePipeline
{
    private array $middlewares = [];

    public function add(MiddlewareInterface $middleware): self
    {
        $this->middlewares[] = $middleware;
        return $this;
    }

    public function process(callable $destination): void
    {
        $pipeline = array_reduce(
            array_reverse($this->middlewares),
            fn ($next, $middleware) =>
                fn () => $middleware->handle($next),
            $destination
        );

        $pipeline();
    }
}