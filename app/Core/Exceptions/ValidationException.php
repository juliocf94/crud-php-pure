<?php

declare(strict_types=1);

namespace App\Core\Exceptions;

final class ValidationException extends HttpException
{
    protected int $statusCode = 422;

    private array $errors;

    public function __construct(
        string $message = 'Validation failed',
        array $errors = []
    ) {
        parent::__construct($message);
        $this->errors = $errors;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}