<?php
/**
 * Clean Output MVC
 *
 * Payload Size Guard Middleware
 *
 * Protects the application against excessively large request payloads
 * by validating the Content-Length header before request processing.
 *
 * This middleware acts as an early guard-layer and should be executed
 * before any controller or domain logic is invoked.
 *
 * It does not parse or inspect the payload body itself.
 *
 * @author    Michael Korte
 * @email     mkorte@korte-software.de
 * @company   Michael Korte Software
 *
 * @version   0.1
 * @date      13.12.2025
 *
 * @package   CHK\Middleware
 */

namespace CHK\Middleware;

use CHK\Core\MiddlewareInterface;
use CHK\Core\Response;

final class PayloadSizeMiddleware implements MiddlewareInterface
{
    /**
     * Maximum allowed payload size in bytes.
     *
     * @var int
     */
    private int $maxBytes;

    /**
     * @param int $maxBytes Maximum payload size in bytes (default: 1 MB)
     */
    public function __construct(int $maxBytes = 1048576)
    {
        $this->maxBytes = $maxBytes;
    }

    /**
     * Handle middleware execution.
     *
     * @param array    $context Middleware context (must contain request)
     * @param callable $next    Next middleware callback
     *
     * @return mixed
     */
    public function handle(array $context, callable $next): mixed
    {
        $request = $context['request'] ?? null;

        // If no request is present, skip payload validation
        if (!$request) {
            return $next($context);
        }

        $length = (int) ($request->getHeader('Content-Length') ?? 0);

        // Reject request if payload exceeds allowed size
        if ($length > 0 && $length > $this->maxBytes) {
            return Response::payloadTooLarge();
        }

        return $next($context);
    }
}