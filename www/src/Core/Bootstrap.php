<?php

namespace CHK\Core;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Twig\TwigFunction;

use CHK\Renderer\ImageRenderer;
use CHK\Renderer\StyleRenderer;
use CHK\Renderer\ScriptRenderer;
use CHK\Renderer\BlockRenderer;

use CHK\Service\SlugGenerator;
use CHK\Repository\ShortUrlRepository;

use CHK\Twig\ImageExtension;

/**
 * Bootstrap
 *
 * Responsible for:
 * - loading configuration
 * - wiring core services
 * - initializing Twig
 * - registering renderer & middleware
 *
 * Bootstrap MUST NOT:
 * - contain business logic
 * - render output
 * - handle requests
 *
 * @author  Michael Korte
 * @email   mkorte@korte-software.de
 * @company Michael Korte Software
 * @version 0.1
 * @date    13.12.2025
 */
final class Bootstrap
{
    /**
     * Load and merge application configuration.
     *
     * - app.php is mandatory
     * - credentials.php is optional and ignored if missing
     *
     * @return array
     */
    private static function loadConfig(): array
    {
        $appConfig = require __DIR__ . '/../../config/app.php';

        $credentialsFile = __DIR__ . '/../../config/credentials.php';
        $credentials     = [];

        if (is_readable($credentialsFile)) {
            $credentials = require $credentialsFile;
        }

        return array_replace_recursive($appConfig, $credentials);
    }

    /**
     * Boot the application and register all core services.
     *
     * @return App
     */
    public static function boot(): App
    {
        $config = self::loadConfig();
        $app    = new App($config);

        /**
         * --------------------------------------------------
         * Twig & View
         * --------------------------------------------------
         */
        $twig = self::bootTwig($app);

        // Project image extension (Twig)
        $twig->addExtension(
            new ImageExtension(
                require __DIR__ . '/../../config/images.php'
            )
        );

        $app->setService('twig', $twig);
        $app->setService('view', new View($twig));

        /**
         * --------------------------------------------------
         * Renderer (Output Orchestrator)
         * --------------------------------------------------
         */
        $app->setService(
            'renderer',
            new Renderer($app)
        );

        /**
         * --------------------------------------------------
         * Core / Infrastructure Services
         * --------------------------------------------------
         */
        // -----------------------------
// Database (optional)
// -----------------------------
if (!empty($config['db']['enabled'])) {
    $app->setService(
        'db',
        new Database($config['db'])
    );
}

        $app->setService(
            'request',
            Request::fromGlobals()
        );

        /**
         * --------------------------------------------------
         * Renderer Services
         * --------------------------------------------------
         */
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

        /**
         * --------------------------------------------------
         * Domain / Project Services
         * --------------------------------------------------
         */
/*
        $app->setService(
            'slugGenerator',
            new SlugGenerator()
        );

        $app->setService(
            'shortRepo',
            new ShortUrlRepository(
                $app->getService('db')->pdo()
            )
        );
*/
        /**
         * --------------------------------------------------
         * Middleware (Guard Layer)
         * --------------------------------------------------
         */
        $app->addMiddleware(
            new \CHK\Middleware\RateLimitMiddleware()
        );

        $app->addMiddleware(
            new \CHK\Middleware\MethodWhitelistMiddleware(['GET', 'POST'])
        );

        $app->addMiddleware(
            new \CHK\Middleware\PayloadSizeMiddleware(1_000_000)
        );

        $app->addMiddleware(
            new \CHK\Middleware\AbuseBurstMiddleware(10, 2)
        );

        return $app;
    }

    /**
     * Setup Twig environment.
     *
     * Registers:
     * - template loaders (project first, core second)
     * - global Twig helpers
     *
     * @param App $app
     * @return Environment
     */
    private static function bootTwig(App $app): Environment
    {
        $loader = new FilesystemLoader([
            __DIR__ . '/../../templates', // project templates
            __DIR__ . '/../templates',    // core templates
        ]);

        $twig = new Environment($loader, [
            'cache'      => false,
            'autoescape' => 'html',
        ]);

        /**
         * image() Twig helper
         *
         * Thin proxy to ImageRenderer.
         */
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
                    ->render(
                        $preset,
                        $name,
                        $alt,
                        $overrides
                    ),
                ['is_safe' => ['html']]
            )
        );

        return $twig;
    }
}