<?php

declare(strict_types=1);

namespace App\Core;

use Throwable;
use App\Core\Exceptions\HttpException;
use App\Core\Exceptions\ValidationException;

final class ExceptionHandler
{
    public static function register(): void
    {
        set_exception_handler([self::class, 'handleException']);
        set_error_handler([self::class, 'handleError']);
    }

    public static function handleException(Throwable $exception): void
    {
        $statusCode = 500;

        if ($exception instanceof HttpException) {
            $statusCode = $exception->getStatusCode();
        }

        http_response_code($statusCode);
        header('Content-Type: application/json');

        $response = [
            'status' => 'error',
            'message' => self::getMessage($exception),
        ];

        // Include validation errors if present
        if ($exception instanceof ValidationException) {
            $response['errors'] = $exception->getErrors();
        }

        if (($_ENV['APP_ENV'] ?? 'production') === 'local') {
            $response['debug'] = [
                'exception' => get_class($exception),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
            ];
        }

        echo json_encode($response);
        exit;
    }

    public static function handleError(
        int $severity,
        string $message,
        string $file,
        int $line
    ): bool {
        throw new \ErrorException($message, 0, $severity, $file, $line);
    }

    private static function getStatusCode(Throwable $exception): int
    {
        return method_exists($exception, 'getCode') && $exception->getCode() >= 400
            ? $exception->getCode()
            : 500;
    }

    private static function getMessage(Throwable $exception): string
    {
        return ($_ENV['APP_ENV'] ?? 'production') === 'local'
            ? $exception->getMessage()
            : 'Internal Server Error';
    }
}
