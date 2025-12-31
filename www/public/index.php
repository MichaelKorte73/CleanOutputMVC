<?php
declare(strict_types=1);

/**
 * Clean Output MVC
 *
 * Front Controller
 *
 * Zentraler Einstiegspunkt der Anwendung.
 *
 * Aufgaben:
 * - Autoload initialisieren
 * - Core booten
 * - Request-Lifecycle starten
 *
 * ❗ KEINE Business-Logik
 * ❗ KEIN Routing
 * ❗ KEIN Rendering
 */

// -------------------------------------------------
// Error-Handling (DEV only, später env-abhängig)
// -------------------------------------------------
ini_set('display_errors', '1');

// -------------------------------------------------
// 1️⃣ Composer Autoload (optional)
// -------------------------------------------------
$composerAutoload = __DIR__ . '/../vendor/autoload.php';
if (is_file($composerAutoload)) {
    require $composerAutoload;
}

// -------------------------------------------------
// 2️⃣ Core Autoloader
// -------------------------------------------------
$coreAutoload = __DIR__ . '/../src/Core/Autoload.php';
if (!is_file($coreAutoload)) {
    throw new RuntimeException('Core Autoload not found.');
}

require $coreAutoload;

\CHK\Core\Autoload::register([
    'CHK\\'        => __DIR__ . '/../src',
    'App\\'        => __DIR__ . '/../custom/app',
    'Components\\' => __DIR__ . '/../custom/components',
    'Plugins\\'    => __DIR__ . '/../custom/plugins',
]);

// -------------------------------------------------
// 3️⃣ Bootstrap prüfen
// -------------------------------------------------
if (!class_exists(\CHK\Core\Bootstrap::class)) {
    throw new RuntimeException(
        'Autoload failed: CHK\Core\Bootstrap not found.'
    );
}

// -------------------------------------------------
// 4️⃣ App booten
// -------------------------------------------------
$app = \CHK\Core\Bootstrap::boot();




// -------------------------------------------------
// 5️⃣ Runtime starten
// -------------------------------------------------
$app->run();