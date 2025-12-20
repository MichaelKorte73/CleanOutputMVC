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

    public static function boot(): App
    {
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

        /* ---------------- Infra Services ---------------- */

        if (!empty($config['db'])) {
            $app->setService('db', new Database($config['db']));
        }

        $app->setService('request', Request::fromGlobals());

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

        $app->addMiddleware(new \CHK\Middleware\RateLimitMiddleware());
        $app->addMiddleware(new \CHK\Middleware\MethodWhitelistMiddleware(['GET', 'POST']));
        $app->addMiddleware(new \CHK\Middleware\PayloadSizeMiddleware(1_000_000));
        $app->addMiddleware(new \CHK\Middleware\AbuseBurstMiddleware(10, 2));

        return $app;
    }

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
}