<?php
/**
 * Security
 *
 * Author: Michael Korte
 * Mail: mkorte@korte-software.de
 * Company: Michael Korte Software
 * Version: 0.1
 * Date: 13.12.2025
 *
 * Applies global HTTP security headers.
 *
 * Responsibilities:
 * - Set standard security-related HTTP headers
 * - Apply Content-Security-Policy if configured
 * - Adjust caching behaviour based on environment
 *
 * Notes:
 * - Called once during application bootstrap
 * - Does not perform request validation
 * - No side effects beyond HTTP headers
 */
namespace CHK\Core;

final class Security
{
    /**
     * Apply security-related HTTP headers.
     *
     * @param array $config Application configuration
     */
    public static function apply(array $config): void
    {
        if (headers_sent()) {
            return;
        }

        // -------------------------------------------------
        // Standard security headers
        // -------------------------------------------------
        header('X-Frame-Options: SAMEORIGIN');
        header('X-Content-Type-Options: nosniff');
        header('Referrer-Policy: strict-origin-when-cross-origin');
        header('X-XSS-Protection: 1; mode=block');

        // -------------------------------------------------
        // Content Security Policy
        // -------------------------------------------------
        if (!empty($config['security']['csp'])) {
            header('Content-Security-Policy: ' . $config['security']['csp']);
        }

        // -------------------------------------------------
        // Environment-specific behaviour
        // -------------------------------------------------
        if (($config['env'] ?? 'prod') === 'dev') {
            header('Cache-Control: no-store, no-cache, must-revalidate');
        }
    }
}