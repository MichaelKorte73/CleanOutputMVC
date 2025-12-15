<?php

namespace CHK\Validator;

/**
 * UrlValidator
 *
 * Simple helper for validating absolute HTTP/HTTPS URLs.
 * Intended for on-demand usage inside controllers or domain logic.
 *
 * This validator performs:
 * - syntactic URL validation
 * - scheme whitelisting (http / https)
 *
 * No side effects, no normalization, no network checks.
 *
 * @author  Michael Korte
 * @email   mkorte@korte-software.de
 * @company Michael Korte Software
 * @version 0.1
 * @date    13.12.2025
 */
final class UrlValidator
{
    /**
     * Validate a URL string.
     *
     * @param string $url Raw URL input
     * @return bool True if URL is valid and uses http/https
     */
    public static function isValid(string $url): bool
    {
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            return false;
        }

        $scheme = parse_url($url, PHP_URL_SCHEME);

        return in_array($scheme, ['http', 'https'], true);
    }
}