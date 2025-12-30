<?php

/**
 * Clean Output MVC
 *
 * Zentrale Konfigurationsdatei der App.
 *
 * Enthält:
 * - Umgebungsdefinition
 * - Asset-Konfiguration (Styles / Scripts)
 * - Component- & Plugin-Registrierung
 * - Fallback-Routing
 * - Logging-Konfiguration
 *
 * ❗ WICHTIG:
 * - Explizite Registrierung
 * - KEINE Auto-Discovery
 * - KEINE Magic Defaults
 */

return [

    /**
     * Aktuelle Laufzeit-Umgebung.
     *
     * Typische Werte:
     * - dev
     * - staging
     * - prod
     */
    'env' => 'dev',

    /**
     * Basis-URL der Anwendung.
     *
     * Wird verwendet für:
     * - absolute Links (z. B. Shortener-Ergebnisse)
     *
     * Kann env-spezifisch überschrieben werden
     * (z. B. via config/credentials.php).
     */
    'base_url' => '',

    /**
     * Image-Konfiguration (Presets, Formate, Pfade).
     *
     * Ausgelagert in images.php.
     */
    'images' => require __DIR__ . '/images.php',

    /**
     * Style-Konfiguration.
     *
     * Wird vom StyleRenderer verarbeitet.
     * Reihenfolge & Auswahl erfolgt über Handles
     * im PageContext (addStyle()).
     */
    'styles' => [

        // ===== Core Styles =====

        'reset' => [
            'id'  => 'style-reset',
            'src' => 'public/assets/css/core/reset.css',
        ],

        'base' => [
            'id'  => 'style-base',
            'src' => 'public/assets/css/core/base.css',
        ],

        'typography' => [
            'id'  => 'style-typography',
            'src' => 'public/assets/css/core/typography.css',
        ],

        'grid' => [
            'id'  => 'style-grid',
            'src' => 'public/assets/css/core/grid.css',
        ],

        'layout' => [
            'id'  => 'style-layout',
            'src' => 'public/assets/css/core/layout.css',
        ],

        'helpers' => [
            'id'  => 'style-helpers',
            'src' => 'public/assets/css/core/helpers.css',
        ],

        'brand' => [
            'id'  => 'style-brand',
            'src' => 'public/assets/css/core/brand.css',
        ],

        'footer' => [
            'id'  => 'style-footer',
            'src' => 'public/assets/css/core/footer.css',
        ],

        'modals' => [
            'id'  => 'style-modals',
            'src' => 'public/assets/css/core/modals.css',
        ],

        // ===== Module Styles =====

        'module' => [
            'id'  => 'style-module',
            'src' => 'public/assets/css/modules/module.css',
        ],

        'messages' => [
            'id'  => 'style-messages',
            'src' => 'public/assets/css/modules/messages.css',
        ],

        'footer_extra' => [
            'id'  => 'style-footer-extra',
            'src' => 'public/assets/css/modules/footer-extra.css',
        ],

        // ===== Tool / Feature Styles =====

        'home' => [
            'id'  => 'style-home',
            'src' => 'public/assets/css/tools/home.css',
        ],

        'shortener' => [
            'id'  => 'style-shortener',
            'src' => 'public/assets/css/tools/shortener.css',
        ],
    ],

    /**
     * Script-Konfiguration.
     *
     * Wird vom ScriptRenderer verarbeitet.
     *
     * Unterstützt:
     * - inline  (lokale Dateien)
     * - external (CDN / Drittanbieter)
     *
     * Gruppen:
     * - top
     * - head
     * - footer
     */
    'scripts' => [

        'core' => [
            'type'  => 'inline',
            'src'   => 'public/assets/js/core/core.js',
            'group' => 'footer',
        ],

        'modals' => [
            'type'  => 'inline',
            'src'   => 'public/assets/js/modules/modals.js',
            'group' => 'footer',
        ],

        'parallax' => [
            'type'  => 'inline',
            'src'   => 'public/assets/js/effects/parallax.js',
            'group' => 'footer',
        ],

        'shortener' => [
            'type'  => 'inline',
            'src'   => 'public/assets/js/tools/shortener.js',
            'group' => 'footer',
        ],

        /*
         * Beispiele für externe Scripts.
         * Aktuell deaktiviert.
         *
         * 'cookiebot' => [
         *     'type'  => 'external',
         *     'src'   => 'https://consent.cookiebot.com/uc.js',
         *     'group' => 'top',
         *     'attrs' => [
         *         'data-cbid' => 'XXXX',
         *         'async'     => true,
         *     ],
         * ],
         *
         * 'recaptcha' => [
         *     'type'  => 'external',
         *     'src'   => 'https://www.google.com/recaptcha/api.js',
         *     'group' => 'head',
         *     'attrs' => [
         *         'defer' => true,
         *     ],
         * ],
         */
    ],

    /**
     * Fallback-Routen.
     *
     * Werden genutzt, wenn kein regulärer
     * Route-Match erfolgt.
     */
    'fallbacks' => [
        404 => [
            'controller' => \CHK\Controller\ErrorController::class,
            'action'     => 'error404',
            'status'     => 404,
        ],
    ],

    /**
     * Component-Registrierung.
     *
     * Explizit.
     * Reihenfolge ist relevant.
     */
    'components' => [
        \Components\Shorten\ShortenComponent::class,
    ],

    /**
     * Plugin-Registrierung.
     *
     * Plugins greifen ausschließlich
     * über Hooks ein.
     */
    'plugins' => [
        // \Plugins\SomePlugin::class,
    ],

    /**
     * Logging-Konfiguration.
     */
    'logging' => [

        // Globales Logging an/aus
        'enabled' => true,

        /**
         * Log-Level-Maske.
         *
         * Beispiel:
         * ERROR | WARNING | INFO
         */
        'mask' =>
            \CHK\Logging\LogLevel::ERROR
          | \CHK\Logging\LogLevel::WARNING
          | \CHK\Logging\LogLevel::INFO,

        /**
         * Ziel für Log-Ausgaben.
         *
         * Aktuell unterstützt:
         * - file
         * - stderr
         */
        'target' => 'file',

        /**
         * Zielpfad für FileTarget.
         */
        'file' => __DIR__ . '/../var/logs/app.log',

        /**
         * Formatter.
         *
         * Implizit:
         * - ID: line
         * - Version: 1
         */
        'format' => 'line-v1',
    ],

    /**
     * Platzhalter für zukünftige Services:
     *
     * 'db'    => …
     * 'cache' => …
     */
];