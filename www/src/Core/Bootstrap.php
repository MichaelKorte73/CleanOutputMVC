<?php
declare(strict_types=1);

/**
 * Clean Output MVC
 *
 * Bootstrap
 *
 * Zentrale System-Bootstrap-Klasse.
 *
 * Verantwortlich für:
 * - Laden & Mergen der Konfiguration
 * - Initialisierung aller Core-Services
 * - Aufbau der App-Instanz
 * - Durchsetzung harter System-Guards (DB, Canonical URL)
 *
 * ❗ Bootstrap enthält KEINE Business-Logik
 * ❗ Bootstrap entscheidet NICHT über Routing
 * ❗ Bootstrap darf den App-Start abbrechen
 *
 * Failure-Policy:
 * - Kritische Infrastruktur (z.B. DB) fehlt → App startet NICHT
 *
 * @package   CHK\Core
 * @author    Michael Korte
 * @license   MIT
 */

namespace CHK\Core;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Twig\TwigFunction;

use CHK\Renderer\ImageRenderer;
use CHK\Renderer\StyleRenderer;
use CHK\Renderer\ScriptRenderer;
use CHK\Renderer\BlockRenderer;

use CHK\Logging\Logger;
use CHK\Logging\NullLogger;
use CHK\Logging\LogLevel;
use CHK\Logging\Formatter\DefaultLineFormatter;
use CHK\Logging\Target\FileTarget;

use CHK\Service\SlugGenerator;
use CHK\Repository\ShortUrlRepository;
use CHK\Twig\ImageExtension;

use App\AppRoutes;

final class Bootstrap
{
    /* ----------------------------------------
       CONFIG
    ---------------------------------------- */

    /**
     * Lädt die Applikationskonfiguration.
     *
     * Reihenfolge:
     * 1. config/app.php
     * 2. config/credentials.php (optional)
     *
     * credentials.php überschreibt app.php rekursiv.
     */
    private static function loadConfig(): array
    {
        $appConfig = require __DIR__ . '/../../config/app.php';

        $credentialsFile = __DIR__ . '/../../config/credentials.php';
        $credentials = [];

        if (is_readable($credentialsFile)) {
            $credentials = require $credentialsFile;
        }

        return array_replace_recursive($appConfig, $credentials);
    }

    /* ----------------------------------------
       BOOT
       → liefert App ODER null (harte Failure-Policy)
    ---------------------------------------- */

    /**
     * Bootstrapped das gesamte System.
     *
     * Ablauf:
     * 1. Canonical-URL-Guard
     * 2. Config laden
     * 3. App instanziieren
     * 4. Core-Services registrieren
     * 5. Projekt-spezifische Erweiterungen laden
     *
     * @return App|null  null bei kritischem Systemfehler
     */
    public static function boot(): ?App
    {
        self::enforceCanonicalUrl();

        $config = self::loadConfig();
        $app    = new App($config);

        /* ---------------- Logger ---------------- */

        $logcfg = $config['logging'] ?? [];

        if (($logcfg['enabled'] ?? false) === true) {
            $logger = new Logger(
                mask: $logcfg['mask'] ?? (LogLevel::ERROR | LogLevel::WARNING),
                formatter: new DefaultLineFormatter(),
                target: new FileTarget(
                    $logcfg['file'] ?? __DIR__ . '/../../var/logs/app.log'
                )
            );
        } else {
            $logger = new NullLogger();
        }

        $app->setService('logger', $logger);

        /* ---------------- Database (HARD GUARD) ---------------- */

        /**
         * Datenbank ist systemkritisch.
         * Bei Verbindungsfehler → App startet NICHT.
         *
        if (!empty($config['db'])) {
            try {
                $app->setService('db', new Database($config['db']));
            } catch (\PDOException $e) {
                $logger->log(
                    LogLevel::CRITICAL,
                    'core',
                    self::class,
                    'Database connection failed',
                    ['exception' => $e->getMessage()]
                );

                return null;
            }
        }
*/
        /* ---------------- Request ---------------- */

        $app->setService('request', Request::fromGlobals());

        /* ---------------- Twig / View ---------------- */

        $twig = self::bootTwig($app);

        $twig->addExtension(
            new ImageExtension(
                require __DIR__ . '/../../config/images.php'
            )
        );

        $app->setService('twig', $twig);
        $app->setService('view', new View($twig));

        /* ---------------- Renderer ---------------- */

        $app->setService('renderer', new Renderer($app));

        /* ---------------- Renderer Services ---------------- */

        $app->setService('imageRenderer', new ImageRenderer($config['images'] ?? []));
        $app->setService('styleRenderer', new StyleRenderer($config['styles'] ?? []));
        $app->setService('scriptRenderer', new ScriptRenderer($config['scripts'] ?? []));
        $app->setService('blockRenderer', new BlockRenderer($twig));

        /* ---------------- Project Services ---------------- */

        $app->setService('slugGenerator', new SlugGenerator());

        if ($app->hasService('db')) {
            $app->setService(
                'shortRepo',
                new ShortUrlRepository(
                    $app->getService('db')->pdo()
                )
            );
        }

        /* ---------------- App Routes ---------------- */

        (new AppRoutes())->registerRoutes($app->getRouter());

        /* ---------------- Components ---------------- */

        $componentsFile = __DIR__ . '/../../config/components.php';
        if (is_file($componentsFile)) {
            require $componentsFile;
        }

        /* ---------------- Plugins ---------------- */

        $pluginsFile = __DIR__ . '/../../config/plugins.php';
        if (is_file($pluginsFile)) {
            require $pluginsFile;
        }

        /* ---------------- Middleware ---------------- */

        /**
         * Reihenfolge ist relevant:
         * - System Guards zuerst
         * - Rate/Abuse danach
         */
        $app->addMiddleware(new \CHK\Middleware\CapabilityMiddleware());
        $app->addMiddleware(new \CHK\Middleware\MethodWhitelistMiddleware(['GET', 'POST']));
        $app->addMiddleware(new \CHK\Middleware\PayloadSizeMiddleware(1_000_000));
        $app->addMiddleware(new \CHK\Middleware\RateLimitMiddleware());
        $app->addMiddleware(new \CHK\Middleware\AbuseBurstMiddleware(10, 2));

        return $app;
    }

    /* ----------------------------------------
       TWIG
    ---------------------------------------- */

    /**
     * Initialisiert Twig inkl. Loader & Core-Funktionen.
     */
    private static function bootTwig(App $app): Environment
    {
        $loader = new FilesystemLoader([
            __DIR__ . '/../../custom/app/templates',
            __DIR__ . '/../templates',
        ]);

        $twig = new Environment($loader, [
            'cache'      => false,
            'autoescape' => 'html',
        ]);

        $twig->addFunction(
            new TwigFunction(
                'image',
                fn (
                    string $preset,
                    string $name,
                    string $alt = '',
                    array $overrides = []
                ) => $app
                    ->getService('imageRenderer')
                    ->render($preset, $name, $alt, $overrides),
                ['is_safe' => ['html']]
            )
        );

        return $twig;
    }

    /* ----------------------------------------
       CANONICAL URL
    ---------------------------------------- */

    /**
     * Erzwingt eine kanonische URL-Struktur.
     *
     * Aktuell:
     * - Entfernt Trailing Slash
     *
     * ❗ Läuft VOR App-Initialisierung
     */
    private static function enforceCanonicalUrl(): void
    {
        if (php_sapi_name() === 'cli') {
            return;
        }

        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        if (!in_array($method, ['GET', 'HEAD'], true)) {
            return;
        }

        $uri  = $_SERVER['REQUEST_URI'] ?? '/';
        $path = parse_url($uri, PHP_URL_PATH) ?? '/';

        if ($path === '/') {
            return;
        }

        if (str_ends_with($path, '/')) {
            $target = rtrim($path, '/');

            $query = $_SERVER['QUERY_STRING'] ?? '';
            if ($query !== '') {
                $target .= '?' . $query;
            }

            header('Location: ' . $target, true, 301);
            exit;
        }
    }
}