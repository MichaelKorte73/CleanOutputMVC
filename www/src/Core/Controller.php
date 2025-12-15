<?php
namespace CHK\Core;

/**
 * Base Controller
 *
 * Responsibilities:
 * - Acts as the orchestration layer between App and domain logic
 * - Creates and prepares PageContext
 * - Delegates rendering to the Renderer service
 *
 * Important:
 * - Controllers contain decision logic, not rendering logic
 * - Controllers must not access globals directly
 * - Controllers must not output HTML themselves
 */
abstract class Controller
{
    /** Application container */
    protected App $app;

    /**
     * @param App $app Application instance
     */
    public function __construct(App $app)
    {
        $this->app = $app;
    }

    // -------------------------------------------------
    // SERVICE & CONFIG PROXY
    // -------------------------------------------------

    /**
     * Access a registered service.
     *
     * @throws \RuntimeException if service is not registered
     */
    protected function service(string $id): mixed
    {
        return $this->app->getService($id);
    }

    /**
     * Access configuration values.
     */
    protected function config(string $key, mixed $default = null): mixed
    {
        return $this->app->config($key, $default);
    }

    // -------------------------------------------------
    // RESPONSE HELPERS
    // -------------------------------------------------

    /**
     * Render a template using the central Renderer.
     *
     * Controllers never render directly.
     * Rendering is delegated to the Renderer service.
     */
    protected function render(string $template, PageContext $page): string
    {
        // Ensure HTTP status is applied early
        http_response_code($page->status);

        // Prefer Renderer if available, fallback to View only
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
     * Return JSON response data.
     *
     * Actual output handling is done by App::sendResponse().
     */
    protected function json(array $data, int $status = 200): array
    {
        http_response_code($status);
        return $data;
    }

    /**
     * Perform an HTTP redirect.
     */
    protected function redirect(string $url, int $status = 302): void
    {
        Response::redirect($url, $status);
    }

    // -------------------------------------------------
    // PAGE CONTEXT
    // -------------------------------------------------

    /**
     * Retrieve the current PageContext instance.
     */
    protected function getPage(): PageContext
    {
        return $this->app->getPage();
    }
}