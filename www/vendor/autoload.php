<?php
/**
 * CHK Autoloader (PSR-4 light)
 */

$autoloadMap = [
    'CHK\\Core\\'       => __DIR__ . '/../src/Core/',
    'CHK\\Media\\'      => __DIR__ . '/../src/Media/',
    'CHK\\Controller\\' => __DIR__ . '/../src/Controller/',
    'CHK\\Repository\\' => __DIR__ . '/../src/Repository/',
    'CHK\\Service\\'    => __DIR__ . '/../src/Service/',
    'CHK\\Validator\\'  => __DIR__ . '/../src/Validator/',
    'CHK\\Middleware\\'  => __DIR__ . '/../src/Middleware/',
'CHK\\Renderer\\'  => __DIR__ . '/../src/Renderer/',
    'CHK\\Security\\'  => __DIR__ . '/../src/Security/',
'CHK\\Twig\\'  => __DIR__ . '/../src/Twig/',
    'Twig\\'     => __DIR__ . '/Twig/',
    'AltoRouter' => __DIR__ . '/AltoRouter/',
];
spl_autoload_register(function (string $class) use ($autoloadMap) {
    foreach ($autoloadMap as $prefix => $baseDir) {
        if (str_starts_with($class, $prefix)) {
            $relative = substr($class, strlen($prefix));
            $file = $baseDir . str_replace('\\', '/', $relative) . '.php';

            if (is_file($file)) {
//var_dump($file);
                require $file;
            }
            return;
        }
    }

    
});

// ---------- FALLBACK: non-namespaced libs ----------
spl_autoload_register(function ($class) {
    if ($class === 'AltoRouter') {
        require __DIR__ . '/../vendor/AltoRouter/AltoRouter.php';
    }
});