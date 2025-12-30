<?php
declare(strict_types=1);

/**
 * Clean Output MVC
 *
 * URL Validator (Deprecated)
 *
 * Einfache Hilfsklasse zur Validierung von URLs.
 *
 * ❗ DEPRECATED:
 * - Diese Klasse ist **kein Core-Contract**
 * - Diente ausschließlich als frühes Beispiel
 * - Wird perspektivisch entfernt
 *
 * Nicht verwenden für:
 * - Security-Validierung
 * - Business-Logik
 * - Input-Guards
 *
 * Beibehaltung aktuell nur zu
 * Dokumentations- und Übergangszwecken.
 *
 * @deprecated
 *
 * @package   CHK\Validator
 * @author    Michael Korte
 * @license   MIT
 */

namespace CHK\Validator;

final class UrlValidator
{
    /**
     * Prüft, ob eine URL syntaktisch gültig ist
     * und ein erlaubtes Scheme verwendet.
     *
     * @deprecated
     *
     * @param string $url
     *
     * @return bool
     */
    public static function isValid(string $url): bool
    {
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            return false;
        }

        $scheme = parse_url($url, PHP_URL_SCHEME);

        return in_array($scheme, ['http', 'https'], true);
    }
}