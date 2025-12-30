<?php

/**
 * Clean Output MVC
 *
 * Image Configuration
 *
 * Zentrale Konfiguration für responsive Bilder.
 *
 * Wird verwendet von:
 * - ImageRenderer
 * - Image Twig Extension
 *
 * Definiert:
 * - unterstützte Bildformate
 * - responsive Breakpoints
 * - Presets für unterschiedliche Einsatzzwecke
 */

return [

    /**
     * Basis-Pfad für Image-Assets (öffentlich).
     *
     * Wird zur Generierung von src/srcset verwendet.
     */
    'base_path' => '/assets/img',

    /**
     * Unterstützte Bildformate.
     *
     * Reihenfolge ist relevant:
     * - erstes Format wird als Fallback verwendet
     */
    'formats' => [
        'webp',
        // 'avif' (optional später)
    ],

    /**
     * Verfügbare Bildbreiten (in Pixel).
     *
     * Werden zur Generierung von srcset-Einträgen genutzt.
     */
    'widths' => [
        480,
        768,
        1024,
        1440,
        1920,
    ],

    /**
     * Image-Presets.
     *
     * Presets definieren:
     * - Seitenverhältnis
     * - Größen-Logik (sizes)
     * - Fallback-Breite
     * - Ladeverhalten
     * - optionale CSS-Klassen
     */
    'presets' => [

        /**
         * Hero-Bilder (above the fold).
         */
        'hero' => [
            'ratio'         => '16x9',
            'sizes'         => '100vw',
            'fallbackWidth' => 1024,
            'loading'       => 'eager',
            'fetchpriority' => 'high',
            'class'         => 'parallax-img',
        ],

        /**
         * Banner-Bilder (groß, aber nicht kritisch).
         */
        'banner' => [
            'ratio'         => '16x9',
            'sizes'         => '100vw',
            'fallbackWidth' => 1024,
            'loading'       => 'lazy',
            'class'         => 'parallax-img',
        ],

        /**
         * Content-Bilder (Textnähe).
         */
        'content' => [
            'ratio'         => '4x3',
            'sizes'         => '(max-width: 768px) 100vw, 50vw',
            'fallbackWidth' => 768,
            'loading'       => 'lazy',
            'class'         => 'ce-image',
        ],
    ],
];