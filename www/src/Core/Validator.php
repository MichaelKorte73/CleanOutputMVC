<?php
/**
 * Validator
 *
 * Author: Michael Korte
 * Mail: mkorte@korte-software.de
 * Company: Michael Korte Software
 * Version: 0.1
 * Date: 13.12.2025
 *
 * Simple validation helpers.
 *
 * Notes:
 * - This class is intentionally minimal.
 * - No side effects, no state, no dependencies.
 * - Project-specific validators should live outside the Core.
 */

namespace CHK\Core;

final class Validator
{
    /**
     * Validate a URL.
     *
     * Rules:
     * - Must be a valid URL
     * - Only http and https schemes are allowed
     *
     * @param string $url
     * @return bool
     */
    public static function url(string $url): bool
    {
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            return false;
        }

        $scheme = parse_url($url, PHP_URL_SCHEME);

        return in_array($scheme, ['http', 'https'], true);
    }
}