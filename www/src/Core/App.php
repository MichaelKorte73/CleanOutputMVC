<?php
namespace CHK\Core;

use CHK\Core\Response;

final class App
{
    private array $config;
    private Router $router;
    private PageContext $page;
    
private MiddlewarePipeline $middleware;

    /** @var array<string, mixed> */
    private array $services = [];

    public function __construct(array $config)
    {
        $this->config = $config;
        $this->router = new Router($config);
        $this->page = new PageContext();
        $this->middleware = new MiddlewarePipeline();
    }

 
public function run(): void
{
    Security::apply($this->config);

    $match = $this->router->match();

    // ---------- NO MATCH ----------
    if ($match === null) {
        Response::html('404 Not Found', 404);
        return;
    }

    // ---------- FALLBACK ----------
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
            throw new \RuntimeException("Fallback controller not found");
        }

        $controller = new $controllerClass($this);

        if (!method_exists($controller, $action)) {
            throw new \RuntimeException("Fallback action not found");
        }

        $response = $controller->$action([]);
        $this->sendResponse($response, $status);
        return;
    }

    // ---------- NORMAL ROUTE ----------
    $target = $match['target'];
    $params = $match['params'] ?? [];

    if ($target['type'] === 'controller') {
        $controllerClass = '\\CHK\\Controller\\' . $target['controller'];
        $action          = $target['action'] ?? 'index';

//var_dump($controllerClass);Exit;

        if (!class_exists($controllerClass)) {
            throw new \RuntimeException("Controller not found");
        }

        $controller = new $controllerClass($this);

        if (!method_exists($controller, $action)) {
            throw new \RuntimeException("Action not found");
        }

        $response = $controller->$action($params);
        $this->sendResponse($response);
        return;
    }

    throw new \RuntimeException('Invalid route target');
}    // -------------------------------------------------
    // RESPONSE HANDLING
    // -------------------------------------------------
    // innerhalb von App

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
    // -------------------------------------------------
    // CONFIG
    // -------------------------------------------------
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

    // -------------------------------------------------
    // CORE GETTERS
    // -------------------------------------------------
    Public function getPage(){return $this->page;}

public function addMiddleware(MiddlewareInterface $middleware): void
{
    $this->middleware->add($middleware);
}
}