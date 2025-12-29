<?php
declare(strict_types=1);

namespace CHK\Core;

use CHK\Core\Response;
use CHK\Logging\LoggerInterface;
use CHK\Logging\NullLogger;
use CHK\Logging\LogLevel;

final class App
{
    private array $config;
    private Router $router;
    private PageContext $page;
    private MiddlewarePipeline $middleware;
    private HookManager $hooks;

    /** @var array<string, mixed> */
    private array $services = [];

    /** @var ComponentInterface[] */
    private array $components = [];

    /** @var PluginInterface[] */
    private array $plugins = [];

    private bool $extensionsRegistered = false;

    public function __construct(array $config)
    {
        $this->config     = $config;
        $this->router     = new Router($config);
        $this->page       = new PageContext();
        $this->middleware = new MiddlewarePipeline();
        $this->hooks      = new HookManager();
    }

    // -------------------------------------------------
    // EXTENSIONS (Components / Plugins)
    // -------------------------------------------------

    public function addComponent(ComponentInterface $component): self
    {
        $this->components[] = $component;
        return $this;
    }

    public function getComponents(): array
    {
        return $this->components;
    }

    public function addPlugin(PluginInterface $plugin): self
    {
        $this->plugins[] = $plugin;
        return $this;
    }

    /**
     * Registrierung ist explizit und passiert genau einmal pro Request
     */
    public function registerExtensions(): void
    {
        if ($this->extensionsRegistered) {
            return;
        }

        $this->extensionsRegistered = true;

        $logger = $this->getLogger();
        $logger->log(LogLevel::DEBUG, 'core', self::class, 'Registering extensions', [
            'components' => count($this->components),
            'plugins'    => count($this->plugins),
        ]);

        foreach ($this->components as $component) {
            $component->register($this);
        }

        foreach ($this->plugins as $plugin) {
            $plugin->register($this->hooks, $this);
        }
    }

    // -------------------------------------------------
    // APPLICATION LIFECYCLE
    // -------------------------------------------------

    public function run(): void
    {
        $logger = $this->getLogger();
        $logger->log(LogLevel::INFO, 'core', self::class, 'App run start');

        // Extensions (Routes / Hooks)
        $this->registerExtensions();

        Security::apply($this->config);

        $match = $this->router->match();

        $logger->log(LogLevel::DEBUG, 'core', self::class, 'Router match', [
            'type' => $match['type'] ?? null,
            'code' => $match['code'] ?? null,
        ]);

        // ---------- FALLBACK ----------
        if (($match['type'] ?? null) === 'fallback') {
            $fallbacks = $this->config('fallbacks', []);
            $fallback  = $fallbacks[$match['code']] ?? null;

            if (!$fallback) {
                Response::html('404 Not Found', 404);
                return;
            }

            $controller = new $fallback['controller']($this);
            $response   = $controller->{$fallback['action']}([]);

            if ($response === null) {
                return;
            }

            $this->sendResponse($response, $fallback['status'] ?? 404);
            return;
        }

        // ---------- NORMAL ROUTE ----------
        $target = $match['target'];
        $params = $match['params'] ?? [];

        if (($target['type'] ?? null) === 'controller') {
            $controller = new $target['controller']($this);
            $action     = $target['action'] ?? 'index';

            $context = [
                'app'     => $this,
                'match'   => $match,
                'target'  => $target,
                'params'  => $params,
                'request' => $this->hasService('request')
                    ? $this->getService('request')
                    : Request::fromGlobals(),
            ];

            $response = $this->middleware->handle(
                $context,
                function (array $ctx) use ($controller, $action) {
                    return $controller->$action($ctx['params'] ?? []);
                }
            );

            /**
             * 🔥 Controller-Contract v0.3
             * null = Controller hat Response selbst ausgegeben
             */
            if ($response === null) {
                return;
            }

            $this->sendResponse($response);
            return;
        }

        $logger->log(LogLevel::ERROR, 'core', self::class, 'Invalid route target', [
            'target' => $target,
        ]);

        throw new \RuntimeException('Invalid route target');
    }

    // -------------------------------------------------
    // RESPONSE HANDLING
    // -------------------------------------------------

    private function sendResponse(mixed $response, int $status = 200): void
    {
        $this->hooks->doAction('app.ready', $this, $response, $status);

        if (is_array($response)) {
            Response::json($response, $status);
            return;
        }

        if (is_string($response)) {
            Response::html($response, $status);
            return;
        }

        throw new \RuntimeException(
            'Unsupported response type: ' . gettype($response)
        );
    }

    // -------------------------------------------------
    // CONFIG
    // -------------------------------------------------

    public function config(?string $key = null, mixed $default = null): mixed
    {
        if ($key === null) {
            return $this->config;
        }

        return $this->config[$key] ?? $default;
    }

    public function hasConfig(string $key): bool
    {
        return array_key_exists($key, $this->config);
    }

    // -------------------------------------------------
    // SERVICES
    // -------------------------------------------------

    public function setService(string $id, mixed $service): void
    {
        $this->services[$id] = $service;
    }

    public function getService(string $id): mixed
    {
        if (!array_key_exists($id, $this->services)) {
            throw new \RuntimeException("Service '{$id}' not registered");
        }

        return $this->services[$id];
    }

    public function hasService(string $id): bool
    {
        return array_key_exists($id, $this->services);
    }

    public function getLogger(): LoggerInterface
    {
        if ($this->hasService('logger')) {
            $logger = $this->getService('logger');
            if ($logger instanceof LoggerInterface) {
                return $logger;
            }
        }

        return new NullLogger();
    }

    public function getHooks(): HookManager
    {
        return $this->hooks;
    }

    // -------------------------------------------------
    // CORE ACCESSORS
    // -------------------------------------------------

    public function getPage(): PageContext
    {
        return $this->page;
    }

    public function getRouter(): Router
    {
        return $this->router;
    }

    // -------------------------------------------------
    // MIDDLEWARE
    // -------------------------------------------------

    public function addMiddleware(MiddlewareInterface $middleware): void
    {
        $this->middleware->add($middleware);
    }
}
