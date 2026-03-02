<?php

declare(strict_types=1);

namespace App\Core\Middleware;

interface MiddlewareInterface
{
    public function handle(callable $next): void;
}