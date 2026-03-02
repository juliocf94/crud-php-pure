<?php

namespace App\Core\Exceptions;

class MethodNotAllowedException extends HttpException
{
    public function __construct(string $message = 'Method Not Allowed', array $allowed = [])
    {
        parent::__construct($message, 405);

        if (!empty($allowed)) {
            header('Allow: ' . implode(', ', $allowed));
        }
    }
}