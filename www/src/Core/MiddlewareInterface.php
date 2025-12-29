<?php
declare(strict_types=1);

/**
 * Clean Output MVC
 *
 * Middleware Interface
 *
 * Middleware ist ein systemnaher Guard-Mechanismus.
 * Sie läuft VOR dem Controller und darf:
 * - Requests abbrechen
 * - Exceptions werfen
 * - Redirects oder direkte Responses auslösen
 *
 * Middleware darf NICHT:
 * - Rendern
 * - PageContext manipulieren
 * - Business-Logik enthalten
 *
 * Typische Aufgaben:
 * - Method Whitelisting
 * - Rate Limiting
 * - Payload-Guards
 * - Capability-Checks
 *
 * @package   CHK\Core
 * @author    Michael Korte
 * @license   MIT
 */

namespace CHK\Core;

interface MiddlewareInterface
{
    /**
     * Verarbeitet den Request-Kontext oder delegiert an die nächste Middleware.
     *
     * @param array    $context   Systemkontext des Requests.
     *                            Typische Keys:
     *                            - app      (App)
     *                            - request  (Request)
     *                            - params   (array)
     *                            - target   (array|null)
     *
     * @param callable $next      Nächste Middleware oder finaler Controller-Dispatcher
     *
     * @return mixed              Rückgabewert wird an die Pipeline weitergereicht.
     *                            null signalisiert: Response wurde bereits ausgegeben.
     */
    public function handle(array $context, callable $next): mixed;
}