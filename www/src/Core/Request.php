<?php
declare(strict_types=1);

/**
 * Clean Output MVC
 *
 * HTTP Request
 *
 * Immutable-ish Wrapper um PHP Superglobals.
 *
 * Verantwortlich für:
 * - gekapselten Zugriff auf $_GET / $_POST / $_SERVER / $_COOKIE / $_FILES
 * - Normalisierung einfacher Request-Informationen
 *
 * ❗ WICHTIG:
 * - KEINE Validierung
 * - KEINE Sanitization
 * - KEINE Business-Logik
 * - KEINE Abhängigkeit von Sessions oder Auth
 *
 * Diese Klasse stellt ausschließlich Rohdaten bereit.
 * Validierung & Interpretation passieren explizit:
 * - im Controller
 * - in Middleware
 *
 * @package   CHK\Core
 * @author    Michael Korte
 * @license   MIT
 */

namespace CHK\Core;

final class Request
{
    /** @var array<string, mixed> */
    private array $get;

    /** @var array<string, mixed> */
    private array $post;

    /** @var array<string, mixed> */
    private array $server;

    /** @var array<string, mixed> */
    private array $cookie;

    /** @var array<string, mixed> */
    private array $files;

    /**
     * Private Konstruktion.
     *
     * Nutzung ausschließlich über fromGlobals().
     * Erlaubt später alternative Request-Quellen
     * (Tests, CLI, Subrequests).
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
     * Erzeugt einen Request aus PHP Superglobals.
     *
     * Zentrale Factory für HTTP-Kontext.
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

    /* ----------------------------------------
       INPUT ACCESS
    ---------------------------------------- */

    /**
     * Zugriff auf GET-Parameter.
     *
     * @param string $key
     * @param mixed  $default
     */
    public function get(string $key, mixed $default = null): mixed
    {
        return $this->get[$key] ?? $default;
    }

    /**
     * Zugriff auf POST-Parameter.
     *
     * @param string $key
     * @param mixed  $default
     */
    public function post(string $key, mixed $default = null): mixed
    {
        return $this->post[$key] ?? $default;
    }

    /* ----------------------------------------
       META
    ---------------------------------------- */

    /**
     * HTTP-Methode (normalisiert, uppercase).
     */
    public function method(): string
    {
        return strtoupper($this->server['REQUEST_METHOD'] ?? 'GET');
    }

    /**
     * Request-URI inkl. Query-String.
     */
    public function uri(): string
    {
        return $this->server['REQUEST_URI'] ?? '/';
    }

    /**
     * Convenience-Check für POST-Requests.
     */
    public function isPost(): bool
    {
        return $this->method() === 'POST';
    }
}