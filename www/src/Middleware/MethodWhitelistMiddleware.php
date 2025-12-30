<?php
declare(strict_types=1);

/**
 * Clean Output MVC
 *
 * Method Whitelist Middleware
 *
 * Systemnahe Guard-Middleware zur Einschränkung
 * erlaubter HTTP-Methoden.
 *
 * Aufgabe:
 * - Blockiert Requests mit nicht erlaubten HTTP-Methoden
 * - Schutz auf Transport-Ebene
 *
 * ❗ WICHTIG:
 * - KEINE User- oder Rollenlogik
 * - KEINE Business-Entscheidungen
 * - KEINE Authentifizierung
 *
 * Diese Middleware prüft ausschließlich
 * die HTTP-Methode des Requests.
 *
 * Enforcement:
 * - HTTP 405 (Method Not Allowed)
 * - harter Abbruch (exit)
 *
 * @package   CHK\Middleware
 * @author    Michael Korte
 * @license   MIT
 */

namespace CHK\Middleware;

use CHK\Core\MiddlewareInterface;

final class MethodWhitelistMiddleware implements MiddlewareInterface
{
    /**
     * Erlaubte HTTP-Methoden.
     *
     * @var string[]
     */
    protected array $allowedMethods = [
        'GET',
        'POST',
    ];

    /**
     * Prüft die HTTP-Methode des Requests.
     *
     * @param array    $context  Request-Kontext der Middleware-Pipeline
     * @param callable $next     Nächste Middleware / Controller
     *
     * @return mixed
     */
    public function handle(array $context, callable $next): mixed
    {
        // Transport-nahe Ermittlung der HTTP-Methode
        $method = strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');

        if (!in_array($method, $this->allowedMethods, true)) {
            // TODO:
            // - Einheitliche Error-Response (Response::?)
            // - Allow-Header setzen
            // - Optional: Logging
            http_response_code(405);
            exit;
        }

        return $next($context);
    }
}