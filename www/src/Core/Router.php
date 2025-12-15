<?php

namespace CHK\Core;

use AltoRouter;

/**
 * Router
 *
 * Thin wrapper around AltoRouter.
 * Responsible only for:
 * - route registration
 * - normalizing AltoRouter match results
 *
 * The Router does NOT:
 * - execute controllers
 * - render responses
 * - apply middleware
 *
 * It always returns a normalized match array
 * so the App can act deterministically.
 *
 * @author  Michael Korte
 * @email   mkorte@korte-software.de
 * @company Michael Korte Software
 * @version 0.1
 * @date    13.12.2025
 */
class Router
{
    /**
     * Internal AltoRouter instance.
     */
    private AltoRouter $router;

    /**
     * @param array $config Application config
     */
    public function __construct(array $config)
    {
        $this->router = new AltoRouter();

        if (!empty($config['base_path'])) {
            $this->router->setBasePath($config['base_path']);
        }

        $this->registerDefaultRoutes();
    }

    /**
     * Register default routes.
     *
     * NOTE:
     * - Order matters (catch-all routes MUST be last)
     * - Project-specific routes may override or extend these
     */
    protected function registerDefaultRoutes(): void
    {
        /*
        // Homepage
        $this->router->map('GET', '/', [
            'type'       => 'controller',
            'controller' => 'HomeController',
            'action'     => 'index',
        ]);

        // Shortener: Create
        $this->router->map('POST', '/s', [
            'type'       => 'controller',
            'controller' => 'ShortenController',
            'action'     => 'create',
        ], 'shorten.create');

        // Formular
        $this->router->map('GET', '/shorten', [
            'controller' => 'HomeController',
            'action'     => 'index',
        ]);

        // Create
        $this->router->map('POST', '/shorten', [
            'controller' => 'ShortenController',
            'action'     => 'create',
        ]);
        */

        // Demo page
        $this->router->map('GET', '/demo', [
            'controller' => 'DemoController',
            'action'     => 'index',
        ]);

        // Slug resolve (explicit alpha slug)
        $this->router->map('GET', '/[a:slug]', [
            'controller' => 'DemoController',
            'action'     => 'index',
        ]);

        // Catch-all resolve (MUST be last!)
        $this->router->map('GET', '/[*:slug]', [
            'type'       => 'controller',
            'controller' => 'DemoController',
            'action'     => 'index',
        ], 'shorten.resolve');
    }

    /**
     * Match the current request.
     *
     * ALWAYS returns a normalized structure.
     *
     * @return array{
     *     type: string,
     *     target?: array,
     *     params?: array,
     *     code?: int
     * }
     */
    public function match(): array
    {
        $match = $this->router->match();

        if ($match) {
            return [
                'type'   => 'route',
                'target' => $this->normalizeTarget($match['target']),
                'params' => $match['params'] ?? [],
            ];
        }

        return [
            'type' => 'fallback',
            'code' => 404,
        ];
    }

    /**
     * Normalize route target definitions.
     *
     * @param mixed $target
     * @return array Normalized target definition
     */
    protected function normalizeTarget($target): array
    {
        // Closure / Callable target
        if (is_callable($target)) {
            return [
                'type'     => 'callable',
                'callable' => $target,
            ];
        }

        // Controller definition
        if (is_array($target)) {
            return [
                'type'       => 'controller',
                'controller' => $target['controller'] ?? null,
                'action'     => $target['action'] ?? null,
            ];
        }

        throw new \RuntimeException('Invalid route target');
    }
}