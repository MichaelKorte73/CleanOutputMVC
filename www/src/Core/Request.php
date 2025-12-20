<?php
namespace CHK\Core;

final class Request
{
    private array $get;
    private array $post;
    private array $server;
    private array $cookie;
    private array $files;

    private function __construct(
        array $get,
        array $post,
        array $server,
        array $cookie,
        array $files
    ) {
        $this->get    = $get;
        $this->post   = $post;
        $this->server = $server;
        $this->cookie = $cookie;
        $this->files  = $files;
    }

    // 🔑 DAS fehlt aktuell
    public static function fromGlobals(): self
    {
        return new self(
            $_GET,
            $_POST,
            $_SERVER,
            $_COOKIE,
            $_FILES
        );
    }

    // --------------------
    // GETTER (sicher)
    // --------------------
    public function get(string $key, mixed $default = null): mixed
    {
        return $this->get[$key] ?? $default;
    }

    public function post(string $key, mixed $default = null): mixed
    {
        return $this->post[$key] ?? $default;
    }

    public function method(): string
    {
        return strtoupper($this->server['REQUEST_METHOD'] ?? 'GET');
    }

    public function uri(): string
    {
        return $this->server['REQUEST_URI'] ?? '/';
    }

    public function isPost(): bool
    {
        return $this->method() === 'POST';
    }
}