<?php
/**
 * Clean Output MVC
 *
 * Rate Limit Middleware (Preparation)
 *
 * Guards the application against excessive request rates per client.
 * This middleware is intentionally minimal in v0.1 and serves as a
 * structural placeholder for future rate-limiting implementations.
 *
 * Current behavior:
 * - Middleware is active
 * - No real rate limiting is enforced yet
 *
 * Future extensions (out of scope for v0.1):
 * - APCu / Redis / File-based counters
 * - Sliding window or token bucket strategies
 * - Per-route or per-endpoint policies
 *
 * IMPORTANT:
 * This middleware belongs to the security guard layer and must run
 * before routing and controller execution.
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

final class RateLimitMiddleware implements MiddlewareInterface
{
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

        // No request present → skip rate limiting
        if (!$request) {
            return $next($context);
        }

        $ip = $request->getIp() ?? 'unknown';

        // Simple placeholder check (v0.1)
        if ($this->isRateLimited($ip)) {
            return Response::tooManyRequests();
        }

        return $next($context);
    }

    /**
     * Determine whether the given client IP is rate limited.
     *
     * NOTE:
     * This method intentionally returns false in v0.1.
     * Storage-backed rate limiting will be introduced in a later version.
     *
     * @param string $ip Client IP address
     *
     * @return bool
     */
    private function isRateLimited(string $ip): bool
    {
        // TODO (future):
        // Implement storage-backed rate handling (APCu, Redis, File, DB)
        return false;
    }
}