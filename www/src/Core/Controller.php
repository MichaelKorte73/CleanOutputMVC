<?php
namespace CHK\Core;

abstract class Controller
{
    protected App $app;
    

    public function __construct(App $app)
    {
        $this->app = $app;
    }

    // -----------------------------
    // Service / Config Proxy
    // -----------------------------
    protected function service(string $id): mixed
    {
        return $this->app->getService($id);
    }

    protected function config(string $key, mixed $default = null): mixed
    {
        return $this->app->config($key, $default);
    }

    // -----------------------------
    // Response Helpers
    // -----------------------------
    protected function render(string $template, PageContext $page): string
    {
        http_response_code($page->status);

;

        // Renderer bevorzugen, sonst View
        if ($this->app->hasService('renderer')) {
            return $this->app->getService('renderer')->render($template, $page);
        }

        return $this->app->getService('view')->render($template, $page);
    }

    protected function json(array $data, int $status = 200): array
    {
        http_response_code($status);
        return $data;
    }

    protected function redirect(string $url, int $status = 302): void
    {
        Response::redirect($url, $status);
    }
    public function getPage(){return $this->app->getPage();}
}