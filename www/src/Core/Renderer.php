<?php
namespace CHK\Core;

use CHK\Renderer\StyleRenderer;
use CHK\Renderer\ScriptRenderer;
use CHK\Renderer\BlockRenderer;

/**
 * Renderer
 *
 * Central output orchestrator.
 *
 * Responsibilities:
 * - Coordinates asset rendering (CSS & JS)
 * - Delegates block rendering
 * - Invokes Twig view rendering
 * - Provides a single, predictable rendering pipeline
 *
 * Important:
 * - Renderer contains NO business logic
 * - Renderer does NOT decide what to render
 * - Renderer only orchestrates already prepared state
 */
final class Renderer
{
    public function __construct(
        private App $app
    ) {}

    /**
     * Render a page using the given Twig template and PageContext.
     *
     * Flow:
     * - renderer.before hook
     * - Render styles
     * - Render scripts (grouped)
     * - Render blocks
     * - Render Twig template
     * - renderer.after hook
     *
     * @param string      $template
     * @param PageContext $page
     *
     * @return string
     */
    public function render(string $template, PageContext $page): string
    {
        Hooks::doAction('renderer.before', $page);

        /** @var StyleRenderer $styles */
        $styles = $this->app->getService('styleRenderer');

        /** @var ScriptRenderer $scripts */
        $scripts = $this->app->getService('scriptRenderer');

        // Render scripts per defined group
        $scriptsTop    = $scripts->renderGroup('top', $page->getScripts());
        $scriptsHead   = $scripts->renderGroup('head', $page->getScripts());
        $scriptsFooter = $scripts->renderGroup('footer', $page->getScripts());

        /** @var BlockRenderer $blockRenderer */
        $blockRenderer = $this->app->getService('blockRenderer');

        // Render block-based content
        $content = $blockRenderer->render(
            $page->getBlocks()
        );

        // Render final HTML via View (Twig)
        $html = $this->app->getService('view')->render(
            $template,
            $page,
            [
                'styles'         => $styles->render($page->getStyles()),
                'scripts_top'    => $scriptsTop,
                'scripts_head'   => $scriptsHead,
                'scripts_footer' => $scriptsFooter,
                'content'        => $content,
            ]
        );

        Hooks::doAction('renderer.after', $page, $html);

        return $html;
    }
}