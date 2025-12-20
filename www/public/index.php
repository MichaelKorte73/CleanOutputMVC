<?php
declare(strict_types=1);

ini_set('display_errors', '1');

/**
 * 1️⃣ Composer laden, falls vorhanden
 */
$composerAutoload = __DIR__ . '/../vendor/autoload.php';
if (is_file($composerAutoload)) {
    require $composerAutoload;
}

/**
 * 2️⃣ Eigenen Autoloader IMMER registrieren
 *    (aber er greift nur, wenn Composer etwas NICHT geliefert hat)
 */
require __DIR__ . '/../src/Core/Autoload.php';

\CHK\Core\Autoload::register([
    'CHK\\'        => __DIR__ . '/../src',
    'App\\'        => __DIR__ . '/../custom/app',
    'Components\\' => __DIR__ . '/../custom/components',
    'Plugins\\'    => __DIR__ . '/../custom/plugins',
]);

/**
 * 3️⃣ Optional: harte Validierung (empfohlen)
 *    → schützt vor halbfertigen Composer-Setups
 */
if (!class_exists(\CHK\Core\Bootstrap::class)) {
    throw new RuntimeException(
        'Autoload failed: CHK\Core\Bootstrap not found. ' .
        'Composer autoload incomplete and internal autoload failed.'
    );
}

/**
 * 4️⃣ Bootstrap starten
 */
$app = \CHK\Core\Bootstrap::boot();
$app->run();