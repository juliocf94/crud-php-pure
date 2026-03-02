<?php

declare(strict_types=1);

namespace App\Core\Exceptions;

final class UnauthorizedException extends HttpException
{
    protected int $statusCode = 401;

    public function __construct(string $message = 'Unauthorized')
    {
        parent::__construct($message);
    }
}