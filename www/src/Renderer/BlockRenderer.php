<?php
declare(strict_types=1);

namespace CHK\Renderer;

use Twig\Environment;

final class BlockRenderer
{
    private Environment $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    public function render(array $blocks): string
    {
        $html = '';

        foreach ($blocks as $index => $block) {
            if (!isset($block->type)) {
                throw new \RuntimeException(
                    "Block at index {$index} has no type"
                );
            }

            $type = $block->type;
            $data = $block->data ?? [];

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