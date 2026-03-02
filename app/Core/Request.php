<?php

namespace App\Core;

class Request
{
    private string $method;
    private string $uri;
    private array $queryParams;
    private array $body;
    private array $headers;

    public function __construct(
        string $method,
        string $uri,
        array $queryParams = [],
        array $body = [],
        array $headers = []
    ) {
        $this->method      = $method;
        $this->uri         = $uri;
        $this->queryParams = $queryParams;
        $this->body        = $body;
        $this->headers     = $headers;
    }

    public static function capture(): self
    {
        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        $uri    = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);

        $headers = function_exists('getallheaders')
            ? getallheaders()
            : [];

        $body = json_decode(file_get_contents('php://input'), true) ?? $_POST;

        return new self(
            $method,
            $uri,
            $_GET,
            $body,
            $headers
        );
    }

    public function method(): string
    {
        return $this->method;
    }

    public function uri(): string
    {
        return $this->uri;
    }

    public function query(string $key, mixed $default = null): mixed
    {
        return $this->queryParams[$key] ?? $default;
    }

    public function input(string $key, mixed $default = null): mixed
    {
        return $this->body[$key] ?? $default;
    }

    public function header(string $key, mixed $default = null): mixed
    {
        return $this->headers[$key] ?? $default;
    }

    public function all(): array
    {
        return $this->body;
    }
}