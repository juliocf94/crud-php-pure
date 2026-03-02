<?php

declare(strict_types=1);

namespace App\Core\Exceptions;

use Exception;

abstract class HttpException extends Exception
{
    protected int $statusCode;

    public function __construct(
        string $message = '',
        ?int $statusCode = null
    ) {
        $this->statusCode = $statusCode ?? $this->statusCode;
        parent::__construct($message, $this->statusCode);
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }
}