<?php
namespace CHK\Core;

use Twig\Environment;

/**
 * View
 *
 * Thin wrapper around Twig.
 *
 * Responsibilities:
 * - Receives prepared data from the Renderer
 * - Passes PageContext and view data to Twig
 * - Performs no logic, no decisions, no rendering orchestration
 *
 * Important:
 * - View does NOT know about controllers
 * - View does NOT know about routing
 * - View does NOT manipulate PageContext
 */
final class View
{
    /** Twig environment */
    private Environment $twig;

    /**
     * @param Environment $twig Configured Twig instance
     */
    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    /**
     * Render a Twig template.
     *
     * @param string      $template Twig template path
     * @param PageContext $page     Current page context
     * @param array       $extra    Additional variables injected by the Renderer
     */
    public function render(
        string $template,
        PageContext $page,
        array $extra = []
    ): string {
        return $this->twig->render(
            $template,
            array_merge(
                $page->getViewData(),
                [
                    'page' => $page,
                ],
                $extra
            )
        );
    }
}