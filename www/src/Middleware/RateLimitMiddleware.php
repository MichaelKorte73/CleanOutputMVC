<?php

namespace CHK\Middleware;

use CHK\Core\MiddlewareInterface;

/**
 * RateLimitMiddleware
 *
 * Guard-Middleware zum Schutz vor Request-Flooding.
 * Arbeitet ausschließlich auf Transport-/Context-Ebene.
 */
class RateLimitMiddleware implements MiddlewareInterface
{
    /**
     * @param array    $context
     * @param callable $next
     * @return mixed
     */
    public function handle(array $context, callable $next): mixed
    {
        // Transport-nahe IP-Ermittlung (bewusste Zwischenlösung)
        $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';

        // TODO:
        // - Rate-Limit-Storage (Memory, File, Redis, etc.)
        // - Zeitfenster definieren
        // - Bei Überschreitung: Response abbrechen (429)
        //
        // Aktuell: Guard ist aktiv, aber transparent.

        return $next($context);
    }
}