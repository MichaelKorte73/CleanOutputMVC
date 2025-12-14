<?php
return [

    'base_path' => '/assets/img',

    'formats' => [
        
        'webp',
    ],

    'widths' => [480, 768, 1024, 1440, 1920],

    'presets' => [

        'hero' => [
            'ratio'         => '16x9',
            'sizes'         => '100vw',
            'fallbackWidth' => 1024,
            'loading'       => 'eager',
            'fetchpriority' => 'high',
            'class'         => 'parallax-img',
        ],

        'banner' => [
            'ratio'         => '16x9',
            'sizes'         => '100vw',
            'fallbackWidth' => 1024,
            'loading'       => 'lazy',
            'class'         => 'parallax-img',
        ],

        'content' => [
            'ratio'         => '4x3',
            'sizes'         => '(max-width: 768px) 100vw, 50vw',
            'fallbackWidth' => 768,
            'loading'       => 'lazy',
            'class'         => 'ce-image',
        ],
    ],
];