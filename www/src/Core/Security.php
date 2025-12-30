<?php
declare(strict_types=1);

/**
 * Clean Output MVC
 *
 * Security
 *
 * Zentrale, systemnahe Security-Header-Konfiguration.
 *
 * Verantwortlich für:
 * - Setzen grundlegender HTTP-Security-Header
 * - optionale Content-Security-Policy (CSP)
 * - umgebungsabhängige Header (z. B. Dev vs. Prod)
 *
 * ❗ Keine Authentifizierung
 * ❗ Keine Benutzer- oder Rollenlogik
 * ❗ Keine Request-Validierung
 *
 * Diese Klasse:
 * - wirkt global pro Request
 * - wird früh im App-Lifecycle ausgeführt
 * - ist bewusst statisch und zustandslos
 *
 * @package   CHK\Core
 * @author    Michael Korte
 * @license   MIT
 */

namespace CHK\Core;

final class Security
{
    /**
     * Wendet Security-relevante HTTP-Header an.
     *
     * Wird typischerweise im App::run() aufgerufen,
     * nachdem Extensions registriert wurden,
     * aber bevor Output erzeugt wird.
     *
     * Header:
     * - X-Frame-Options
     * - X-Content-Type-Options
     * - Referrer-Policy
     * - X-XSS-Protection
     * - Content-Security-Policy (optional)
     *
     * Environment-Abhängigkeiten:
     * - Dev: Cache deaktiviert
     *
     * ❗ Falls bereits Header gesendet wurden,
     *    wird die Methode stillschweigend beendet.
     *
     * @param array $config  Gesamte App-Konfiguration
     */
    public static function apply(array $config): void
    {
        if (headers_sent()) {
            return;
        }

        // --- Standard Security Header ---
        header('X-Frame-Options: SAMEORIGIN');
        header('X-Content-Type-Options: nosniff');
        header('Referrer-Policy: strict-origin-when-cross-origin');
        header('X-XSS-Protection: 1; mode=block');

        // --- Content Security Policy ---
        if (!empty($config['security']['csp'])) {
            header('Content-Security-Policy: ' . $config['security']['csp']);
        }

        // --- Environment-dependent ---
        if (($config['env'] ?? 'prod') === 'dev') {
            header('Cache-Control: no-store, no-cache, must-revalidate');
        }
    }
}