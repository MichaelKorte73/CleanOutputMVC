<?php
declare(strict_types=1);

/**
 * Clean Output MVC
 *
 * Block Renderer
 *
 * Renderer-Komponente zur serverseitigen Ausgabe
 * von Block-Strukturen.
 *
 * Aufgabe:
 * - Rendert eine Liste von Block-Objekten
 * - Mappt Block-Typen auf Twig-Templates
 *
 * ❗ WICHTIG:
 * - KEINE Mutation des PageContext
 * - KEINE Business-Logik
 * - KEINE Fallback-Magie
 *
 * BlockRenderer ist eine reine
 * Rendering-Komponente.
 *
 * Erwartete Block-Struktur:
 * - type : string   (Pflicht)
 * - data : array    (optional)
 *
 * Template-Auflösung:
 * - blocks/{type}.html.twig
 *
 * Fehlerverhalten:
 * - fehlender Typ → RuntimeException
 * - Render-Fehler → RuntimeException (mit Cause)
 *
 * @package   CHK\Renderer
 * @author    Michael Korte
 * @license   MIT
 */

namespace CHK\Renderer;

use Twig\Environment;
use RuntimeException;
use Throwable;

final class BlockRenderer
{
    private Environment $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    /**
     * Rendert eine Liste von Blocks zu HTML.
     *
     * @param array $blocks  Liste von Block-Objekten
     *
     * @return string
     */
    public function render(array $blocks): string
    {
        $html = '';

        foreach ($blocks as $index => $block) {
            if (!isset($block->type)) {
                throw new RuntimeException(
                    "Block at index {$index} has no type"
                );
            }

            $type = $block->type;
            $data = $block->data ?? [];

            $template = "blocks/{$type}.html.twig";

            try {
                $html .= $this->twig->render($template, $data);
            } catch (Throwable $e) {
                throw new RuntimeException(
                    "Failed rendering block '{$type}'",
                    0,
                    $e
                );
            }
        }

        return $html;
    }
}