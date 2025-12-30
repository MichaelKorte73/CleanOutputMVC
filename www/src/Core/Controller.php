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
 * - Zugriff auf App, Services und Config
 * - Aufbau und Nutzung des PageContext
 * - Orchestrierung von Responses
 *
 * Controller-Contract (v0.3):
 * Eine Action darf zurückgeben:
 * - string  → HTML (Core sendet Response)
 * - array   → JSON (Core sendet Response)
 * - null    → Controller hat Response selbst vollständig ausgegeben
 *
 * ❗ Controller rendern KEINEN Output direkt
 * ❗ Controller entscheiden NICHT über Transport (HTML/JSON)
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

    /**
     * Zugriff auf die zentrale App-Instanz.
     */
    protected function app(): App
    {
        return $this->app;
    }

    /**
     * Zugriff auf den aktuellen PageContext.
     */
    protected function page(): PageContext
    {
        return $this->app->getPage();
    }

    /* ----------------------------------------
       SERVICES / CONFIG
    ---------------------------------------- */

    /**
     * Zugriff auf einen registrierten Service.
     *
     * @throws \RuntimeException wenn Service nicht existiert
     */
    protected function service(string $id): mixed
    {
        return $this->app->getService($id);
    }

    /**
     * Zugriff auf Konfiguration.
     */
    protected function config(string $key, mixed $default = null): mixed
    {
        return $this->app->config($key, $default);
    }

    /* ----------------------------------------
       CAPABILITIES
    ---------------------------------------- */

    /**
     * Prüft, ob eine Capability im aktuellen Kontext erlaubt ist.
     */
    protected function can(string $capability): bool
    {
        return $this->app->can($capability);
    }

    /**
     * Erzwingt eine Capability.
     *
     * @throws \RuntimeException wenn Capability nicht erlaubt ist
     */
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
     * @return string HTML – Ausgabe erfolgt durch den Core
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
     * JSON-Response.
     *
     * @return array Daten – Serialisierung erfolgt durch den Core
     */
    protected function json(array $data, int $status = 200): array
    {
        http_response_code($status);
        return $data;
    }

    /**
     * Redirect.
     *
     * ❗ Controller gibt die Response selbst aus
     * ❗ Core verarbeitet danach nichts mehr
     *
     * @return never
     */
    protected function redirect(string $url, int $status = 302): never
    {
        Response::redirect($url, $status);
    }
}