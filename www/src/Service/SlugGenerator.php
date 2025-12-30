<?php
declare(strict_types=1);

/**
 * Clean Output MVC
 *
 * Slug Generator
 *
 * Systemnahe Utility-Klasse zur Erzeugung
 * kurzer, zufälliger Slugs / Identifiers.
 *
 * Aufgabe:
 * - Generiert zufällige, URL-taugliche Strings
 *
 * ❗ WICHTIG:
 * - KEINE Kollisionserkennung
 * - KEINE Persistenz
 * - KEINE semantische Slug-Logik
 *
 * Diese Klasse ist bewusst simpel
 * und deterministisch im Verhalten.
 *
 * @package   CHK\Service
 * @author    Michael Korte
 * @license   MIT
 */

namespace CHK\Service;

final class SlugGenerator
{
    /**
     * Erzeugt einen zufälligen Slug.
     *
     * @param int $length  Länge des Slugs
     *
     * @return string
     */
    public function generate(int $length = 6): string
    {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $slug  = '';

        for ($i = 0; $i < $length; $i++) {
            $slug .= $chars[random_int(0, strlen($chars) - 1)];
        }

        return $slug;
    }
}