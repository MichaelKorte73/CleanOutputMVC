<?php

namespace CHK\Middleware;

use CHK\Core\MiddlewareInterface;

/**
 * AbuseBurstMiddleware
 *
 * Guard-Middleware zur Erkennung von kurzfristigen Request-Bursts
 * (z. B. sehr viele Requests in sehr kurzer Zeit).
 *
 * Arbeitet bewusst ausschließlich auf Transport-/Context-Ebene.
 */
class AbuseBurstMiddleware implements MiddlewareInterface
{
    /**
     * Maximale Anzahl Requests im Zeitfenster.
     *
     * @var int
     */
    protected int $maxRequests = 20;

    /**
     * Zeitfenster in Sekunden.
     *
     * @var int
     */
    protected int $timeWindow = 5;

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
        // - Burst-Zähler implementieren (z. B. Cache / File / Memory)
        // - Zeitfenster berücksichtigen
        // - Bei Überschreitung: Response abbrechen (429)
        //
        // Aktuell: Middleware ist aktiv, aber transparent.

        return $next($context);
    }
}