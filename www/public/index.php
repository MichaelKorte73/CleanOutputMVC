<?php
declare(strict_types=1);

ini_set('display_errors', '1');

/**
 * 1️⃣ Composer Autoload
 */
$composerAutoload = __DIR__ . '/../vendor/autoload.php';
if (is_file($composerAutoload)) {
    require $composerAutoload;
}

/**
 * 2️⃣ Interner Autoloader
 */
require __DIR__ . '/../src/Core/Autoload.php';

\CHK\Core\Autoload::register([
    'CHK\\'        => __DIR__ . '/../src',
    'App\\'        => __DIR__ . '/../custom/app',
    'Components\\' => __DIR__ . '/../custom/components',
    'Plugins\\'    => __DIR__ . '/../custom/plugins',
]);

/**
 * 3️⃣ Bootstrap prüfen
 */
if (!class_exists(\CHK\Core\Bootstrap::class)) {
    throw new RuntimeException(
        'Autoload failed: CHK\Core\Bootstrap not found.'
    );
}

/**
 * 4️⃣ App booten
 */
$app = \CHK\Core\Bootstrap::boot();

if ($app === null) {
    http_response_code(503);
    require __DIR__ . '/error/db-unavailable.php';
    exit;
}

$app->run();