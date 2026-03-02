<?php

declare(strict_types=1);

namespace App\Core\Middleware;

final class CorsMiddleware implements MiddlewareInterface
{
    public function handle(callable $next): void
    {
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, Authorization');

        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            http_response_code(204);
            exit;
        }

        $next();
    }
}