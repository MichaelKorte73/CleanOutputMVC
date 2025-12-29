<?php
declare(strict_types=1);

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

                // ❌ App darf NICHT starten
                return null;
            }
        }

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

        $app->setService(
            'imageRenderer',
            new ImageRenderer($config['images'] ?? [])
        );
        $app->setService(
            'styleRenderer',
            new StyleRenderer($config['styles'] ?? [])
        );
        $app->setService(
            'scriptRenderer',
            new ScriptRenderer($config['scripts'] ?? [])
        );
        $app->setService(
            'blockRenderer',
            new BlockRenderer($twig)
        );

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
       CANONICAL URL (Trailing Slash)
    ---------------------------------------- */
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