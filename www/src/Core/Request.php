<?php

namespace CHK\Core;

/**
 * Request
 *
 * Immutable wrapper around PHP superglobals.
 *
 * Responsibilities:
 * - Provide safe access to request data
 * - Normalize request method and URI
 * - Act as input boundary for controllers & middleware
 *
 * Notes:
 * - No validation logic
 * - No mutation
 * - No magic
 *
 * Validation and security checks are handled by middleware,
 * not by the Request object itself.
 */
final class Request
{
    private array $get;
    private array $post;
    private array $server;
    private array $cookie;
    private array $files;

    /**
     * Private constructor.
     * Use fromGlobals() to create an instance.
     */
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

    /**
     * Create a Request instance from PHP superglobals.
     *
     * @return self
     */
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

    // -------------------------------------------------
    // Input accessors
    // -------------------------------------------------

    /**
     * Get a value from the query string.
     */
    public function get(string $key, mixed $default = null): mixed
    {
        return $this->get[$key] ?? $default;
    }

    /**
     * Get a value from POST data.
     */
    public function post(string $key, mixed $default = null): mixed
    {
        return $this->post[$key] ?? $default;
    }

    // -------------------------------------------------
    // Request metadata
    // -------------------------------------------------

    /**
     * Get the HTTP request method.
     */
    public function method(): string
    {
        return strtoupper($this->server['REQUEST_METHOD'] ?? 'GET');
    }

    /**
     * Get the request URI.
     */
    public function uri(): string
    {
        return $this->server['REQUEST_URI'] ?? '/';
    }

    /**
     * Check whether the request is a POST request.
     */
    public function isPost(): bool
    {
        return $this->method() === 'POST';
    }
}