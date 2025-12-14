<?php
namespace CHK\Core;

use AltoRouter;

class Router
{
    private AltoRouter $router;

    public function __construct(array $config)
    {
        $this->router = new AltoRouter();

        if (!empty($config['base_path'])) {
            $this->router->setBasePath($config['base_path']);
        }

        $this->registerDefaultRoutes();
    }
protected function registerDefaultRoutes(): void
{
/*
    // Homepage
    $this->router->map('GET', '/', [
        'type' => 'controller',
        'controller' => 'HomeController',
        'action' => 'index'
    ]);

    // Shortener: Create
    $this->router->map('POST', '/s', [
        'type'       => 'controller',
        'controller' => 'ShortenController',
        'action'     => 'create'
    ], 'shorten.create');

// Formular
$this->router->map('GET', '/shorten', [
    'controller' => 'HomeController',
    'action'     => 'index'
]);

// Create
$this->router->map('POST', '/shorten', [
    'controller' => 'ShortenController',
    'action'     => 'create'
]);
*/
$this->router->map('GET', '/demo', [
    'controller' => 'DemoController',
    'action'     => 'index'
]);
// Resolve
$this->router->map('GET', '/[a:slug]', [
    'controller' => 'DemoController',
    'action'     => 'index'
]);
    // Shortener: Resolve (MUSS zuletzt!)
    $this->router->map('GET', '/[*:slug]', [
        'type'       => 'controller',
        'controller' => 'DemoController',
        'action'     => 'index'
    ], 'shorten.resolve');
}
    
    /**
     * Liefert IMMER ein normiertes Match-Array
     */
    // Core/Router.php
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
    protected function normalizeTarget($target): array
    {
        // Closure / Callable
        if (is_callable($target)) {
            return [
                'type'     => 'callable',
                'callable' => $target,
            ];
        }

        // Controller-Definition
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