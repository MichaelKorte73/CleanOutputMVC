<?php
declare(strict_types=1);

/**
 * Clean Output MVC
 *
 * Base Controller
 *
 * Gemeinsame Basisklasse für alle Controller im System.
 * Definiert den verbindlichen Controller-Contract.
 *
 * Verantwortlich für:
 * - Zugriff auf App & Services
 * - Aufbau von PageContext
 * - Rückgabe von Responses (string | array | null)
 *
 * ❗ Controller rendern NICHT direkt Output
 * ❗ Controller orchestrieren, sie entscheiden nicht über Transport
 *
 * @package   CHK\Core
 * @author    Michael Korte
 * @license   MIT
 */

namespace CHK\Core;

abstract class Controller
{
    protected App $app;

    public function __construct(App $app)
    {
        $this->app = $app;
    }

    /* ----------------------------------------
       CORE ACCESS
    ---------------------------------------- */

    protected function app(): App
    {
        return $this->app;
    }

    protected function page(): PageContext
    {
        return $this->app->getPage();
    }

    /* ----------------------------------------
       SERVICES / CONFIG
    ---------------------------------------- */

    protected function service(string $id): mixed
    {
        return $this->app->getService($id);
    }

    protected function config(string $key, mixed $default = null): mixed
    {
        return $this->app->config($key, $default);
    }

    /* ----------------------------------------
       CAPABILITIES
    ---------------------------------------- */

    protected function can(string $capability): bool
    {
        return $this->app->can($capability);
    }

    protected function requireCapability(string $capability): void
    {
        $this->app->requireCapability($capability);
    }

    /* ----------------------------------------
       RESPONSE HELPERS
    ---------------------------------------- */

    /**
     * Rendert eine Seite.
     *
     * @return string HTML (Renderer übernimmt Output)
     */
    protected function render(string $template, PageContext $page): string
    {
        // Status wird ausschließlich über PageContext gesteuert
        http_response_code($page->status);

        if ($this->app->hasService('renderer')) {
            return $this->app
                ->getService('renderer')
                ->render($template, $page);
        }

        return $this->app
            ->getService('view')
            ->render($template, $page);
    }

    /**
     * JSON-Response (Transport entscheidet Core)
     *
     * @return array
     */
    protected function json(array $data, int $status = 200): array
    {
        http_response_code($status);
        return $data;
    }

    /**
     * Redirect (Controller beendet Response selbst)
     *
     * @return never
     */
    protected function redirect(string $url, int $status = 302): never
    {
        Response::redirect($url, $status);
        exit;
    }
}