<?php
declare(strict_types=1);

/**
 * Clean Output MVC
 *
 * Rate Limit Policy (Deprecated)
 *
 * Einfache, session-basierte Rate-Limit-Policy.
 *
 * ❗ DEPRECATED:
 * - Diese Klasse ist **kein Core-Contract**
 * - Session-basierte Logik ist nicht Core-tauglich
 * - Wird durch Middleware-basierte Guards ersetzt
 *
 * Nicht geeignet für:
 * - verteilte Systeme
 * - API-Schutz
 * - robuste Abuse-Prevention
 *
 * Beibehaltung aktuell nur aus
 * historischen / Übergangsgründen.
 *
 * @deprecated
 *
 * @package   CHK\Security
 * @author    Michael Korte
 * @license   MIT
 */

namespace CHK\Security;

final class RateLimitPolicy
{
    private int $limit;
    private int $window;

    public function __construct(int $limit = 30, int $window = 60)
    {
        $this->limit  = $limit;
        $this->window = $window;
    }

    /**
     * Prüft, ob ein Schlüssel innerhalb
     * des aktuellen Zeitfensters erlaubt ist.
     *
     * ❗ DEPRECATED:
     * - Session-basierte Implementierung
     * - Keine Core-Garantie
     *
     * @deprecated
     *
     * @param string $key
     *
     * @return bool
     */
    public function allow(string $key): bool
    {
        $now = time();

        if (!isset($_SESSION['rpl'][$key])) {
            $_SESSION['rpl'][$key] = [
                'count' => 1,
                'start' => $now,
            ];
            return true;
        }

        $entry = &$_SESSION['rpl'][$key];

        // Zeitfenster abgelaufen → Reset
        if (($now - $entry['start']) > $this->window) {
            $entry = [
                'count' => 1,
                'start' => $now,
            ];
            return true;
        }

        // Limit erreicht
        if ($entry['count'] >= $this->limit) {
            return false;
        }

        $entry['count']++;

        return true;
    }
}