<?php

declare(strict_types=1);

namespace App\Core\Middleware;

use App\Core\Exceptions\UnauthorizedException;

final class AuthMiddleware implements MiddlewareInterface
{
    public function handle(callable $next): void
    {
        $authHeader = $_SERVER['HTTP_AUTHORIZATION'] ?? null;

        if (!$authHeader) {
            throw new UnauthorizedException('Missing Authorization header');
        }

        // Simple example
        if ($authHeader !== 'Bearer secret-token') {
            throw new UnauthorizedException('Invalid token');
        }

        $next();
    }
}