<?php
declare(strict_types=1);

/**
 * Clean Output MVC
 *
 * Abuse Burst Middleware
 *
 * Systemnahe Guard-Middleware zur Erkennung
 * kurzfristiger Request-Bursts
 * (z. B. viele Requests in sehr kurzer Zeit).
 *
 * Aufgabe:
 * - Erkennen von auffälligen Request-Spitzen
 * - Schutz vor einfachen Flood-/Burst-Angriffen
 *
 * ❗ WICHTIG:
 * - KEINE User- oder Rollenlogik
 * - KEINE Authentifizierung
 * - KEINE Business-Entscheidungen
 * - KEINE Persistenz-Vorgabe
 *
 * Diese Middleware arbeitet ausschließlich
 * auf Transport- und Context-Ebene.
 *
 * Aktueller Stand:
 * - Middleware ist aktiv
 * - Enforcement ist NO-OP (transparent)
 *
 * Geplante Erweiterung:
 * - Burst-Zähler (Cache / File / Memory)
 * - Zeitfenster-Auswertung
 * - Abbruch mit HTTP 429 (Too Many Requests)
 *
 * @package   CHK\Middleware
 * @author    Michael Korte
 * @license   MIT
 */

namespace CHK\Middleware;

use CHK\Core\MiddlewareInterface;

final class AbuseBurstMiddleware implements MiddlewareInterface
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
     * Prüft Requests auf kurzfristige Burst-Muster.
     *
     * @param array    $context  Request-Kontext der Middleware-Pipeline
     * @param callable $next     Nächste Middleware / Controller
     *
     * @return mixed
     */
    public function handle(array $context, callable $next): mixed
    {
        // Transport-nahe IP-Ermittlung (bewusste Zwischenlösung)
        $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';

        // TODO:
        // - Burst-Zähler implementieren (Cache / File / Memory)
        // - Zeitfenster berücksichtigen
        // - Thresholds
        // - Bei Überschreitung: Abbruch mit HTTP 429
        //
        // Aktuell: Middleware ist aktiv, aber transparent.

        return $next($context);
    }
}