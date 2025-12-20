<?php
namespace CHK\Core;

class Security
{
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

        // --- CSP ---
        if (!empty($config['security']['csp'])) {
            header('Content-Security-Policy: ' . $config['security']['csp']);
        }

        // --- Env-dependent ---
        if (($config['env'] ?? 'prod') === 'dev') {
            header('Cache-Control: no-store, no-cache, must-revalidate');
        }
    }
}