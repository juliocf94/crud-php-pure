<?php

declare(strict_types=1);

namespace App\Core\Exceptions;

final class NotFoundException extends HttpException
{
    protected int $statusCode = 404;

    public function __construct(string $message = 'Resource not found')
    {
        parent::__construct($message);
    }
}