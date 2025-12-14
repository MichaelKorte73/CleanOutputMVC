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

final class Bootstrap
{
    /**
     * --------------------------------------------------
     * Load & merge config
     * --------------------------------------------------
     */
    private static function loadConfig(): array
    {
        $appConfig = require __DIR__ . '/../../config/app.php';

        $credentialsFile = __DIR__ . '/../../config/credentials.php';
        $credentials = [];

        if (is_readable($credentialsFile)) {
            $credentials = require $credentialsFile;
        }

        return array_replace_recursive(
            $appConfig,
            $credentials
        );
    }

    /**
     * --------------------------------------------------
     * Boot application
     * --------------------------------------------------
     */
    public static function boot(): App
    {
        $config = self::loadConfig();
        $app    = new App($config);

        

        /**
         * -----------------------------
         * Twig + View
         * -----------------------------
         */
        


         $twig = self::bootTwig($app);

$twig->addExtension(
    new \CHK\Twig\ImageExtension(
        require __DIR__ . '/../../config/images.php'
    )
);


         $app->setService('twig', $twig);
         $app->setService('view', new View($twig));

         // optional, aber stabil
         $app->setService('renderer', new Renderer($app));

        /**
         * -----------------------------
         * Renderer (Output Orchestrator)
         * -----------------------------
         */
        $app->setService(
            'renderer',
            new Renderer($app)
        );

/**
         * -----------------------------
         * Core / Infra Services
         * -----------------------------
         */
        $app->setService(
            'db',
            new Database($config['db'])
        );

        $app->setService(
            'request',
            Request::fromGlobals()
        );

        /**
         * -----------------------------
         * Renderer Services
         * -----------------------------
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
         * -----------------------------
         * Domain / Project Services
         * -----------------------------
         */
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

        /**
         * -----------------------------
         * Middleware 
         * -----------------------------
         */

        $app->addMiddleware(
            new \CHK\Middleware\RateLimitMiddleware()
        );

        $app->addMiddleware(
            new    \CHK\Middleware\MethodWhitelistMiddleware(['GET', 'POST'])
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
     * --------------------------------------------------
     * Twig setup
     * --------------------------------------------------
     */
    private static function bootTwig(App $app): Environment
    {
        $loader = new FilesystemLoader(
[__DIR__ . '/../../templates',
__DIR__ . '/../templates']);

        $twig = new Environment($loader, [
            'cache'      => false,
            'autoescape' => 'html',
        ]);

        /**
         * image() Twig helper
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