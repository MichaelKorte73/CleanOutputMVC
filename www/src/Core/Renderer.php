<?php
declare(strict_types=1);

/**
 * Clean Output MVC
 *
 * Renderer
 *
 * Zentrale Rendering-Orchestrierung.
 *
 * Verantwortlich für:
 * - Ausführung des Rendering-Lifecycles
 * - Zusammenführung von:
 *   - Styles
 *   - Scripts (Top / Head / Footer)
 *   - Blocks
 *   - View-Daten
 *
 * ❗ Der Renderer erzeugt KEIN HTML selbst
 * ❗ Der Renderer kennt KEINE Templatestruktur
 * ❗ Der Renderer orchestriert nur vorhandene Renderer & View
 *
 * Rendering-Reihenfolge:
 * 1. 🔔 Hook: renderer.before
 * 2. Styles rendern
 * 3. Scripts rendern (top / head / footer)
 * 4. Blocks rendern
 * 5. View rendern
 * 6. 🔔 Hook: renderer.after
 *
 * @package   CHK\Core
 * @author    Michael Korte
 * @license   MIT
 */

namespace CHK\Core;

use CHK\Media\StyleRenderer;
use CHK\Media\ScriptRenderer;

final class Renderer
{
    public function __construct(
        private App $app
    ) {}

    /**
     * Rendert eine Seite.
     *
     * @param string      $template Template-Datei
     * @param PageContext $page     Aktueller PageContext
     *
     * @return string HTML-Ausgabe
     */
    public function render(string $template, PageContext $page): string
    {
        // 🔔 Pre-Render Hook
        Hooks::doAction('renderer.before', $page);

        /** @var StyleRenderer $styles */
        $styles = $this->app->getService('styleRenderer');

        /** @var ScriptRenderer $scripts */
        $scripts = $this->app->getService('scriptRenderer');

        // Script-Gruppen
        $scripts_top    = $scripts->renderGroup('top', $page->getScripts());
        $scripts_head   = $scripts->renderGroup('head', $page->getScripts());
        $scripts_footer = $scripts->renderGroup('footer', $page->getScripts());

        /** @var BlockRenderer $blockRenderer */
        $blockRenderer = $this->app->getService('blockRenderer');

        // Blocks rendern
        $content = $blockRenderer->render(
            $page->getBlocks()
        );

        $data = $page->getViewData();

        // View rendern
        $html = $this->app->getService('view')->render(
            $template,
            $page,
            [
                'styles'         => $styles->render($page->getStyles()),
                'scripts_top'    => $scripts_top,
                'scripts_head'   => $scripts_head,
                'scripts_footer' => $scripts_footer,
                'content'        => $content,
                'data'           => $data,
            ]
        );

        // 🔔 Post-Render Hook
        Hooks::doAction('renderer.after', $page, $html);

        return $html;
    }
}