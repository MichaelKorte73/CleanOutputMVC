<?php
declare(strict_types=1);

/**
 * Clean Output MVC
 *
 * Validator
 *
 * Sammlung einfacher, statischer Validierungshelfer.
 *
 * ❗ Kein State
 * ❗ Keine Abhängigkeiten
 * ❗ Keine Framework-Logik
 *
 * Diese Klasse ist bewusst:
 * - klein
 * - deterministisch
 * - überall nutzbar (Core, Components, Controller, Middleware)
 *
 * Erweiterungen erfolgen additiv (neue Methoden),
 * nicht durch Konfiguration oder Magic.
 *
 * @package   CHK\Core
 * @author    Michael Korte
 * @license   MIT
 */

namespace CHK\Core;

final class Validator
{
    /**
     * Prüft, ob eine URL valide ist.
     *
     * Regeln:
     * - Muss syntaktisch eine gültige URL sein
     * - Erlaubte Schemes: http, https
     *
     * ❗ Keine Erreichbarkeitsprüfung
     * ❗ Kein DNS-Check
     *
     * @param string $url
     * @return bool
     */
    public static function url(string $url): bool
    {
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            return false;
        }

        $scheme = parse_url($url, PHP_URL_SCHEME);
        if (!in_array($scheme, ['http', 'https'], true)) {
            return false;
        }

        return true;
    }
}