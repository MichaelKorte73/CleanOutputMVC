<?php
declare(strict_types=1);

/**
 * Clean Output MVC
 *
 * Payload Size Middleware
 *
 * Systemnahe Guard-Middleware zur Begrenzung der
 * maximal erlaubten Request-Payload-Größe.
 *
 * Aufgabe:
 * - Schutz vor übergroßen Request-Bodies
 * - Früher Abbruch auf Transport-Ebene
 *
 * ❗ WICHTIG:
 * - KEINE User- oder Rollenlogik
 * - KEINE Business-Validierung
 * - KEIN Parsing des Payloads
 *
 * Diese Middleware prüft ausschließlich
 * die deklarierte Payload-Größe (CONTENT_LENGTH).
 *
 * Enforcement:
 * - HTTP 413 (Payload Too Large)
 * - harter Abbruch (exit)
 *
 * @package   CHK\Middleware
 * @author    Michael Korte
 * @license   MIT
 */

namespace CHK\Middleware;

use CHK\Core\MiddlewareInterface;

final class PayloadSizeMiddleware implements MiddlewareInterface
{
    /**
     * Maximale erlaubte Payload-Größe in Bytes.
     *
     * @var int
     */
    protected int $maxPayloadSize = 1_048_576; // 1 MB

    /**
     * Prüft die Größe des eingehenden Request-Payloads.
     *
     * @param array    $context  Request-Kontext der Middleware-Pipeline
     * @param callable $next     Nächste Middleware / Controller
     *
     * @return mixed
     */
    public function handle(array $context, callable $next): mixed
    {
        // Transport-nahe Payload-Ermittlung
        $contentLength = isset($_SERVER['CONTENT_LENGTH'])
            ? (int) $_SERVER['CONTENT_LENGTH']
            : 0;

        if ($contentLength > $this->maxPayloadSize) {
            // TODO:
            // - Einheitliche Error-Response (Response::?)
            // - Optional: Logging
            http_response_code(413);
            exit;
        }

        return $next($context);
    }
}