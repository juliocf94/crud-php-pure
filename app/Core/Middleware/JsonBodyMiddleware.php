<?php

declare(strict_types=1);

namespace App\Core\Middleware;

use App\Core\Exceptions\ValidationException;

final class JsonBodyMiddleware implements MiddlewareInterface
{
    public function handle(callable $next): void
    {
        $contentType = $_SERVER['CONTENT_TYPE'] ?? '';

        if (str_contains($contentType, 'application/json')) {
            $body = file_get_contents('php://input');
            $decoded = json_decode($body, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new ValidationException('Invalid JSON body');
            }

            $_REQUEST = array_merge($_REQUEST, $decoded ?? []);
        }

        $next();
    }
}