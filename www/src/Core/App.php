<?php
namespace CHK\Core;

use CHK\Core\Response;

/**
 * Core Application
 *
 * Responsibilities:
 * - Orchestrates the full request lifecycle
 * - Applies security headers
 * - Dispatches routing results
 * - Executes controllers
 * - Sends final responses
 *
 * Important:
 * - App itself does NOT render output
 * - Rendering is delegated to Renderer
 * - Business logic lives in Controllers / Services
 */
final class App
{
    /** @var array<string,mixed> */
    private array $config;

    private Router $router;
    private PageContext $page;
    private MiddlewarePipeline $middleware;

    /** @var array<string,mixed> */
    private array $services = [];

    /**
     * @param array<string,mixed> $config
     */
    public function __construct(array $config)
    {
        $this->config     = $config;
        $this->router     = new Router($config);
        $this->page       = new PageContext();
        $this->middleware = new MiddlewarePipeline();
    }

    /**
     * Run application lifecycle.
     *
     * Flow:
     * - Apply security headers
     * - Match route
     * - Resolve controller or fallback
     * - Execute controller action
     * - Send response
     *
     * @return void
     */
    public function run(): void
    {
        Security::apply($this->config);

        $match = $this->router->match();

        /**
         * --------------------------------------------------
         * Fallback handling (e.g. 404)
         * --------------------------------------------------
         */
        if (($match['type'] ?? null) === 'fallback') {
            $fallbacks = $this->config('fallbacks', []);
            $fallback  = $fallbacks[$match['code']] ?? null;

            if (!$fallback) {
                Response::html('404 Not Found', 404);
                return;
            }

            $controllerClass = '\\CHK\\Controller\\' . $fallback['controller'];
            $action          = $fallback['action'];
            $status          = $fallback['status'] ?? 404;

            if (!class_exists($controllerClass)) {
                throw new \RuntimeException('Fallback controller not found');
            }

            $controller = new $controllerClass($this);

            if (!method_exists($controller, $action)) {
                throw new \RuntimeException('Fallback action not found');
            }

            $response = $controller->$action([]);
            $this->sendResponse($response, $status);
            return;
        }

        /**
         * --------------------------------------------------
         * Normal route dispatch
         * --------------------------------------------------
         */
        $target = $match['target'] ?? null;
        $params = $match['params'] ?? [];

        if ($target && $target['type'] === 'controller') {
            $controllerClass = '\\CHK\\Controller\\' . $target['controller'];
            $action          = $target['action'] ?? 'index';

            if (!class_exists($controllerClass)) {
                throw new \RuntimeException('Controller not found');
            }

            $controller = new $controllerClass($this);

            if (!method_exists($controller, $action)) {
                throw new \RuntimeException('Action not found');
            }

            $response = $controller->$action($params);
            $this->sendResponse($response);
            return;
        }

        throw new \RuntimeException('Invalid route target');
    }

    /**
     * Send controller response to client.
     *
     * Supported response types:
     * - null   → empty response with status code
     * - array  → JSON
     * - string → HTML
     *
     * @param mixed $response
     * @param int   $status
     *
     * @return void
     */
    private function sendResponse(mixed $response, int $status = 200): void
    {
        Hooks::doAction('app.ready', $this, $response, $status);

        if ($response === null) {
            http_response_code($status);
            return;
        }

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

    /**
     * Read configuration value.
     *
     * @param string|null $key
     * @param mixed       $default
     *
     * @return mixed
     */
    public function config(?string $key = null, mixed $default = null): mixed
    {
        if ($key === null) {
            return $this->config;
        }

        if (!array_key_exists($key, $this->config)) {
            if (func_num_args() === 2) {
                return $default;
            }

            throw new \RuntimeException("Config key '{$key}' not found");
        }

        return $this->config[$key];
    }

    /**
     * Check if config key exists.
     */
    public function hasConfig(string $key): bool
    {
        return array_key_exists($key, $this->config);
    }

    /**
     * Register a service.
     */
    public function setService(string $id, mixed $service): void
    {
        $this->services[$id] = $service;
    }

    /**
     * Retrieve a service.
     */
    public function getService(string $id): mixed
    {
        if (!array_key_exists($id, $this->services)) {
            throw new \RuntimeException("Service '{$id}' not registered");
        }

        return $this->services[$id];
    }

    /**
     * Check if service exists.
     */
    public function hasService(string $id): bool
    {
        return array_key_exists($id, $this->services);
    }

    /**
     * Get PageContext instance.
     */
    public function getPage(): PageContext
    {
        return $this->page;
    }

    /**
     * Add middleware to pipeline.
     */
    public function addMiddleware(MiddlewareInterface $middleware): void
    {
        $this->middleware->add($middleware);
    }
}