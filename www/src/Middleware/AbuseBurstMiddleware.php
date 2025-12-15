<?php
/**
 * Clean Output MVC
 *
 * Abuse Burst Protection Middleware
 *
 * Protects the application against short-term request bursts
 * by limiting the number of requests per IP + User-Agent
 * within a defined time window.
 *
 * This middleware is intentionally simple and memory-based:
 * - suitable for early abuse protection
 * - no persistence
 * - no external storage (Redis, DB, etc.)
 *
 * It is designed as a guard-layer middleware and must run
 * early in the middleware pipeline.
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

final class AbuseBurstMiddleware implements MiddlewareInterface
{
    /**
     * Maximum allowed hits within the time window.
     *
     * @var int
     */
    private int $maxHits;

    /**
     * Time window in seconds.
     *
     * @var int
     */
    private int $window;

    /**
     * In-memory hit storage.
     *
     * Keyed by hash(IP + User-Agent).
     *
     * @var array<string, array{count:int, ts:int}>
     */
    private static array $hits = [];

    /**
     * @param int $maxHits Maximum requests allowed
     * @param int $window  Time window in seconds
     */
    public function __construct(int $maxHits = 10, int $window = 2)
    {
        $this->maxHits = $maxHits;
        $this->window  = $window;
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

        // If no request is present, skip abuse protection
        if (!$request) {
            return $next($context);
        }

        $ip    = $request->getIp() ?? 'unknown';
        $agent = substr(
            $request->getHeader('User-Agent') ?? 'na',
            0,
            120
        );

        $key = sha1($ip . '|' . $agent);
        $now = time();

        $entry = self::$hits[$key] ?? [
            'count' => 0,
            'ts'    => $now,
        ];

        // Window expired → reset counter
        if ($now - $entry['ts'] > $this->window) {
            $entry = [
                'count' => 0,
                'ts'    => $now,
            ];
        }

        $entry['count']++;
        self::$hits[$key] = $entry;

        // Rate limit exceeded
        if ($entry['count'] > $this->maxHits) {
            return Response::tooManyRequests();
        }

        return $next($context);
    }
}