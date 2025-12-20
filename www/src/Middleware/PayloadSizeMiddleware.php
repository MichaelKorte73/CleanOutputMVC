<?php

namespace CHK\Middleware;

use CHK\Core\MiddlewareInterface;

/**
 * PayloadSizeMiddleware
 *
 * Guard-Middleware zur Begrenzung der Request-Payload-Größe.
 * Arbeitet bewusst ausschließlich auf Transport-/Context-Ebene.
 */
class PayloadSizeMiddleware implements MiddlewareInterface
{
    /**
     * Maximale erlaubte Payload-Größe in Bytes.
     *
     * @var int
     */
    protected int $maxPayloadSize = 1048576; // 1 MB

    /**
     * @param array    $context
     * @param callable $next
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
            // - Sauberen 413-Response erzeugen
            // - Optional: Logging
            http_response_code(413);
            exit;
        }

        return $next($context);
    }
}