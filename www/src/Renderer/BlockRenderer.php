<?php
/**
 * Clean Output MVC
 *
 * Block Renderer
 *
 * Responsible for rendering configured content blocks
 * into HTML fragments using Twig templates.
 *
 * The BlockRenderer:
 * - receives already validated block configurations
 * - maps block types to Twig templates
 * - renders blocks sequentially
 *
 * It does NOT:
 * - mutate the PageContext
 * - contain business logic
 * - decide layout or structure
 *
 * @author    Michael Korte
 * @email     mkorte@korte-software.de
 * @company   Michael Korte Software
 *
 * @version   0.1
 * @date      13.12.2025
 *
 * @package   CHK\Renderer
 */

namespace CHK\Renderer;

use Twig\Environment;

final class BlockRenderer
{
    /**
     * Twig environment instance.
     *
     * @var Environment
     */
    private Environment $twig;

    /**
     * @param Environment $twig Twig environment
     */
    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    /**
     * Render a list of blocks into HTML.
     *
     * Each block must define a `type` which maps to a
     * Twig template located at:
     *
     *     blocks/<type>.html.twig
     *
     * The block's `data` array is passed directly to the template.
     *
     * @param array $blocks Block configuration array
     *
     * @return string Rendered HTML
     *
     * @throws \RuntimeException If a block is invalid or rendering fails
     */
    public function render(array $blocks): string
    {
        $html = '';

        foreach ($blocks as $index => $block) {
            if (!isset($block['type'])) {
                throw new \RuntimeException(
                    "Block at index {$index} has no type"
                );
            }

            $type = $block['type'];
            $data = $block['data'] ?? [];

            $template = "blocks/{$type}.html.twig";

            try {
                $html .= $this->twig->render($template, $data);
            } catch (\Throwable $e) {
                throw new \RuntimeException(
                    "Failed rendering block '{$type}'",
                    0,
                    $e
                );
            }
        }

        return $html;
    }
}