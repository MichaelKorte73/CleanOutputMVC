<?php
declare(strict_types=1);

/**
 * Clean Output MVC
 *
 * Core Application
 *
 * Zentrale Runtime des Frameworks.
 * Verantwortlich für:
 * - Lifecycle (Bootstrap → Routing → Controller → Response)
 * - Verwaltung von Services
 * - Registrierung von Components & Plugins
 * - Capability Registry & Enforcement (v0.3)
 *
 * @package   CHK\Core
 * @author    Michael Korte
 * @license   MIT
 */

namespace CHK\Core;

use CHK\Logging\LoggerInterface;
use CHK\Logging\NullLogger;
use CHK\Logging\LogLevel;

final class App
{
    /* ----------------------------------------
       CORE STATE
    ---------------------------------------- */

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

    /**
     * Capability Registry
     *
     * key   = capability name
     * value = providing component class
     */
    private array $capabilities = [];

    private bool $extensionsRegistered = false;

    public function __construct(array $config)
    {
        $this->config     = $config;
        $this->router     = new Router($config);
        $this->page       = new PageContext();
        $this->middleware = new MiddlewarePipeline();
        $this->hooks      = new HookManager();
    }

    /* ----------------------------------------
       EXTENSIONS
    ---------------------------------------- */

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
     * Registrierung erfolgt genau einmal pro Request.
     *
     * Reihenfolge:
     * 1. Components::register()
     * 2. Plugins::register()
     * 3. 🔔 Hook: components.ready
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

        /**
         * 🔔 Lifecycle-Hook:
         * Alle Components & Plugins sind registriert,
         * aber es wurde noch kein Request verarbeitet.
         */
        $this->hooks->doAction('components.ready', $this);
    }

    /* ----------------------------------------
       CAPABILITIES (Registry)
    ---------------------------------------- */

    public function registerCapability(string $name, string $provider): void
    {
        if (isset($this->capabilities[$name])) {
            throw new \RuntimeException(
                "Capability '{$name}' already registered"
            );
        }

        $this->capabilities[$name] = $provider;
    }

    public function hasCapability(string $name): bool
    {
        return isset($this->capabilities[$name]);
    }

    public function getCapabilities(): array
    {
        return $this->capabilities;
    }

    /* ----------------------------------------
       CAPABILITIES (Enforcement)
    ---------------------------------------- */

    public function can(string $capability): bool
    {
        if (!$this->hasCapability($capability)) {
            return false;
        }

        if (!$this->hasService('permissionResolver')) {
            return true;
        }

        $resolver = $this->getService('permissionResolver');

        return $resolver->isAllowed($capability, $this);
    }

    public function requireCapability(string $capability): void
    {
        if (!$this->can($capability)) {
            throw new \RuntimeException(
                "Capability '{$capability}' is not allowed in current context"
            );
        }
    }

    /* ----------------------------------------
       APPLICATION LIFECYCLE
    ---------------------------------------- */

    public function run(): void
    {
        $logger = $this->getLogger();
        $logger->log(LogLevel::INFO, 'core', self::class, 'App run start');

        $this->registerExtensions();
        Security::apply($this->config);

        $match = $this->router->match();

        if (($match['type'] ?? null) === 'fallback') {
            $fallbacks = $this->config('fallbacks', []);
            $fallback  = $fallbacks[$match['code']] ?? null;

            if (!$fallback) {
                Response::html('404 Not Found', 404);
                return;
            }

            $controller = new $fallback['controller']($this);
            $response   = $controller->{$fallback['action']}([]);

            if ($response !== null) {
                $this->sendResponse($response, $fallback['status'] ?? 404);
            }

            return;
        }

        $target = $match['target'];
        $params = $match['params'] ?? [];

        if (($target['type'] ?? null) === 'controller') {
            $controller = new $target['controller']($this);
            $action     = $target['action'] ?? 'index';

            $context = [
                'app'     => $this,
                'params'  => $params,
                'request' => $this->hasService('request')
                    ? $this->getService('request')
                    : Request::fromGlobals(),
            ];

            $response = $this->middleware->handle(
                $context,
                fn (array $ctx) => $controller->$action($ctx['params'] ?? [])
            );

            if ($response !== null) {
                $this->sendResponse($response);
            }

            return;
        }

        throw new \RuntimeException('Invalid route target');
    }

    /* ----------------------------------------
       RESPONSE
    ---------------------------------------- */

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

    /* ----------------------------------------
       SERVICES
    ---------------------------------------- */

    public function setService(string $id, mixed $service): void
    {
        $this->services[$id] = $service;
    }

    public function getService(string $id): mixed
    {
        if (!isset($this->services[$id])) {
            throw new \RuntimeException("Service '{$id}' not registered");
        }

        return $this->services[$id];
    }

    public function hasService(string $id): bool
    {
        return isset($this->services[$id]);
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

    /* ----------------------------------------
       ACCESSORS
    ---------------------------------------- */

    public function getHooks(): HookManager
    {
        return $this->hooks;
    }

    public function getPage(): PageContext
    {
        return $this->page;
    }

    public function getRouter(): Router
    {
        return $this->router;
    }

    public function addMiddleware(MiddlewareInterface $middleware): void
    {
        $this->middleware->add($middleware);
    }
}
