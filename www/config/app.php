<?php
return [
    'env' => 'dev',

    /**
     * Base URL used for absolute links (e.g. shortener result).
     * Can be overridden via config/credentials.php or other env-specific config.
     */
    'base_url' => '',

    'images' => require __DIR__ . '/images.php',
    'styles' => [

    // ===== Core =====
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

    // ===== Modules =====
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

    // ===== Tools =====
    'home' => [
        'id'  => 'style-home',
        'src' => 'public/assets/css/tools/home.css',
    ],

    'shortener' => [
        'id'  => 'style-shortener',
        'src' => 'public/assets/css/tools/shortener.css',
    ],

],
    'scripts'=> [
          
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
   

    'cookiebot' => [
        'type' => 'external',
        'src'  => 'https://consent.cookiebot.com/uc.js',
        'group'=> 'top',
        'attrs'=> [
            'data-cbid' => 'XXXX',
            'async'     => true,
        ]
    ],

    'recaptcha' => [
        'type'  => 'external',
        'src'   => 'https://www.google.com/recaptcha/api.js',
        'group' => 'head',
        'attrs' => [
            'defer' => true,
        ]
    ],
*/
],

    'fallbacks' => [
        404 => [
            'controller' => '\CHK\Controller\ErrorController',
            'action'     => 'error404',
            'status'     => 404,
        ],
    ],

    /**
     * Components / Plugins (v0.2)
     * Explizite Registrierung – keine Auto-Discovery.
     */
    'components' => [
        \Components\Shorten\ShortenComponent::class,
    ],

    'plugins' => [
        // \Plugins\SomePlugin::class,
    ],

   'logging' => [
        'enabled' => true,

        // Bitmask: was geloggt wird
        // Beispiel: ERROR + WARNING + INFO
        'mask' => 
            \CHK\Logging\LogLevel::ERROR
          | \CHK\Logging\LogLevel::WARNING
          | \CHK\Logging\LogLevel::INFO,

        // Ziel (ein Target = ein Format)
        'target' => 'file',

        // Pfad für FileTarget
        'file' => __DIR__ . '/../var/logs/app.log',

        // Formatter-ID (implizit line-v1)
        'format' => 'line-v1',
    ],
    // später:
    // 'db' => …
    // 'cache' => … 
];