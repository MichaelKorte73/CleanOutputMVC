<?php
declare(strict_types=1);

/**
 * Clean Output MVC
 *
 * Front Controller
 *
 * Zentrale Einstiegspunkt-Datei der Anwendung.
 *
 * Aufgaben:
 * - Initialisiert Autoloading
 * - Bootstrapped das Framework
 * - Startet die App-Runtime
 *
 * ❗ WICHTIG:
 * - KEINE Business-Logik
 * - KEINE Routing-Logik
 * - KEINE Rendering-Logik
 *
 * Diese Datei ist bewusst klein,
 * explizit und deterministisch.
 */

ini_set('display_errors', '1');

/**
 * 1️⃣ Composer Autoload
 *
 * Lädt externe Abhängigkeiten (z. B. Twig).
 * Optional – Anwendung funktioniert auch ohne vendor/.
 */
$composerAutoload = __DIR__ . '/../vendor/autoload.php';
if (is_file($composerAutoload)) {
    require $composerAutoload;
}

/**
 * 2️⃣ Interner Autoloader
 *
 * Framework-eigener Autoloader
 * für Core, App, Components und Plugins.
 */
require __DIR__ . '/../src/Core/Autoload.php';

\CHK\Core\Autoload::register([
    'CHK\\'        => __DIR__ . '/../src',
    'App\\'        => __DIR__ . '/../custom/app',
    'Components\\' => __DIR__ . '/../custom/components',
    'Plugins\\'    => __DIR__ . '/../custom/plugins',
]);

/**
 * 3️⃣ Bootstrap-Verfügbarkeit prüfen
 *
 * Harte Fail-Policy:
 * - Ohne Bootstrap kein Weiterlaufen
 */
if (!class_exists(\CHK\Core\Bootstrap::class)) {
    throw new RuntimeException(
        'Autoload failed: CHK\Core\Bootstrap not found.'
    );
}

/**
 * 5️⃣ Runtime starten
 *
 * Ab hier übernimmt die App
 * den vollständigen Request-Lifecycle.
 */
$app->run();