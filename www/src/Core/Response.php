<?php
declare(strict_types=1);

/**
 * Clean Output MVC
 *
 * HTTP Response Helper
 *
 * Statische Hilfsklasse zur Ausgabe von Responses.
 *
 * Verantwortlich für:
 * - Setzen von HTTP-Statuscodes
 * - Setzen minimaler, korrekter Header
 * - Direkte Ausgabe an den Client
 *
 * ❗ WICHTIG:
 * - KEINE Template-Logik
 * - KEINE Serialisierungs-Policies
 * - KEIN Output-Buffering
 * - KEINE Middleware-Logik
 *
 * Diese Klasse ist ein *terminaler* Output-Layer.
 * Nach Aufruf sollte kein weiterer Code mehr ausgeführt werden
 * (insbesondere bei redirect()).
 *
 * @package   CHK\Core
 * @author    Michael Korte
 * @license   MIT
 */

namespace CHK\Core;

final class Response
{
    /**
     * Gibt HTML aus.
     *
     * Erwartet vollständig gerenderten Content.
     *
     * @param string $content  HTML-Output
     * @param int    $status   HTTP-Statuscode (Default: 200)
     */
    public static function html(string $content, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: text/html; charset=UTF-8');
        echo $content;
    }

    /**
     * Gibt JSON aus.
     *
     * Erwartet ein bereits strukturiertes Array.
     * KEINE Domain-Objekte.
     *
     * @param array $data    JSON-Daten
     * @param int   $status  HTTP-Statuscode (Default: 200)
     */
    public static function json(array $data, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=UTF-8');
        echo json_encode($data, JSON_UNESCAPED_SLASHES);
    }

    /**
     * Führt einen HTTP-Redirect aus.
     *
     * ❗ Beendet die Ausführung mittels exit.
     *
     * @param string $url     Ziel-URL
     * @param int    $status  HTTP-Statuscode (Default: 301)
     */
    public static function redirect(string $url, int $status = 301): void
    {
        http_response_code($status);
        header('Location: ' . $url);
        exit;
    }
}