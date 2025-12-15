<?php

namespace CHK\Service;

/**
 * SlugGenerator
 *
 * Generates short, random, URL-safe slugs.
 * Intended for identifiers such as short URLs or tokens.
 *
 * Characteristics:
 * - cryptographically secure randomness (random_int)
 * - alphanumeric output only
 * - no collision handling (caller responsibility)
 *
 * This service does not persist, validate uniqueness,
 * or encode semantic meaning.
 *
 * @author  Michael Korte
 * @email   mkorte@korte-software.de
 * @company Michael Korte Software
 * @version 0.1
 * @date    13.12.2025
 */
final class SlugGenerator
{
    /**
     * Generate a random slug.
     *
     * @param int $length Length of the generated slug
     * @return string Random alphanumeric slug
     */
    public function generate(int $length = 6): string
    {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $slug  = '';

        $maxIndex = strlen($chars) - 1;

        for ($i = 0; $i < $length; $i++) {
            $slug .= $chars[random_int(0, $maxIndex)];
        }

        return $slug;
    }
}