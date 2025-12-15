<?php

namespace CHK\Security;

/**
 * RateLimitPolicy
 *
 * Simple rate limit decision logic based on a sliding time window.
 * This class contains no HTTP or framework logic and can be used
 * by middleware or other infrastructure components.
 *
 * Storage is intentionally simple (session-based) for v0.1 and
 * can be replaced later (APCu, Redis, DB).
 *
 * @author  Michael Korte
 * @email   mkorte@korte-software.de
 * @company Michael Korte Software
 * @version 0.1
 * @date    13.12.2025
 */
final class RateLimitPolicy
{
    /**
     * Maximum number of allowed requests per window.
     */
    private int $limit;

    /**
     * Time window in seconds.
     */
    private int $window;

    /**
     * @param int $limit  Maximum requests per window
     * @param int $window Window length in seconds
     */
    public function __construct(int $limit = 30, int $window = 60)
    {
        $this->limit  = $limit;
        $this->window = $window;
    }

    /**
     * Decide whether a request identified by the given key is allowed.
     *
     * @param string $key Unique identifier (e.g. IP + User-Agent hash)
     * @return bool True if request is allowed, false if rate-limited
     */
    public function allow(string $key): bool
    {
        $now = time();

        if (!isset($_SESSION['rpl'][$key])) {
            $_SESSION['rpl'][$key] = [
                'count' => 1,
                'start' => $now,
            ];
            return true;
        }

        $entry = &$_SESSION['rpl'][$key];

        // Window expired → reset counter
        if (($now - $entry['start']) > $this->window) {
            $entry = [
                'count' => 1,
                'start' => $now,
            ];
            return true;
        }

        // Limit reached
        if ($entry['count'] >= $this->limit) {
            return false;
        }

        $entry['count']++;

        return true;
    }
}