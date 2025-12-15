<?php

return [
    'env' => 'dev',

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
    ],

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
    ],

    'fallbacks' => [
        404 => [
            'controller' => 'ErrorController',
            'action'     => 'error404',
            'status'     => 404,
        ],
    ],
];