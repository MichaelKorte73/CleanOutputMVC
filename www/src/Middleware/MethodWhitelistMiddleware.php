<?php

namespace CHK\Middleware;

use CHK\Core\MiddlewareInterface;

/**
 * MethodWhitelistMiddleware
 *
 * Guard-Middleware zur Einschränkung erlaubter HTTP-Methoden.
 * Arbeitet bewusst ausschließlich auf Transport-/Context-Ebene.
 */
class MethodWhitelistMiddleware implements MiddlewareInterface
{
    /**
     * @var array
     */
    protected array $allowedMethods = [
        'GET',
        'POST',
    ];

    /**
     * @param array    $context
     * @param callable $next
     * @return mixed
     */
    public function handle(array $context, callable $next): mixed
    {
        // Transport-nahe Ermittlung der HTTP-Methode
        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        $method = strtoupper($method);

        if (!in_array($method, $this->allowedMethods, true)) {
            // TODO:
            // - Sauberen 405-Response erzeugen
            // - Allow-Header setzen
            // - ggf. Logging
            http_response_code(405);
            exit;
        }

        return $next($context);
    }
}