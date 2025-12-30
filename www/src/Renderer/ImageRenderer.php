<?php
declare(strict_types=1);

/**
 * Clean Output MVC
 *
 * Image Renderer
 *
 * Renderer-Komponente zur serverseitigen Ausgabe
 * responsiver Bilder via <picture>-Element.
 *
 * Aufgabe:
 * - Rendert Images anhand vordefinierter Presets
 * - Erzeugt <source>-Tags für verschiedene Formate
 * - Liefert ein <img>-Fallback
 *
 * ❗ WICHTIG:
 * - KEINE Bildgenerierung
 * - KEINE Dateisystem-Manipulation
 * - KEINE Fallback-Magie bei fehlenden Assets
 *
 * ImageRenderer ist eine reine
 * Ausgabe- und Mapping-Komponente.
 *
 * @package   CHK\Renderer
 * @author    Michael Korte
 * @license   MIT
 */

namespace CHK\Renderer;

use InvalidArgumentException;

final class ImageRenderer
{
    /**
     * Image-Konfiguration.
     *
     * @var array
     */
    private array $config;

    public function __construct(?array $imageConfig = null)
    {
        $this->config = $imageConfig ?? [];
    }

    /**
     * Rendert ein Bild anhand eines Presets.
     *
     * @param string $preset
     * @param string $name
     * @param string $alt
     * @param array  $overrides
     *
     * @return string
     */
    public function render(
        string $preset,
        string $name,
        string $alt = '',
        array $overrides = []
    ): string {
        if (!isset($this->config['presets'][$preset])) {
            throw new InvalidArgumentException(
                "Unknown image preset: {$preset}"
            );
        }

        $cfg = array_merge(
            $this->config['presets'][$preset],
            $overrides
        );

        $html = "<picture>\n";

        foreach ($this->config['formats'] as $format) {
            $html .= sprintf(
                "<source type=\"image/%s\" srcset=\"%s\" sizes=\"%s\">\n",
                $format,
                $this->buildSrcset($name, $cfg['ratio'], $format),
                $cfg['sizes']
            );
        }

        $fallback = sprintf(
            '%s/%s-%s-%d.webp',
            $this->config['base_path'],
            $name,
            $cfg['ratio'],
            $cfg['fallbackWidth']
        );

        $fetch = isset($cfg['fetchpriority'])
            ? ' fetchpriority="' . $cfg['fetchpriority'] . '"'
            : '';

        $html .= sprintf(
            '<img src="%s" alt="%s" loading="%s" decoding="async" class="%s"%s>',
            $fallback,
            htmlspecialchars($alt, ENT_QUOTES, 'UTF-8'),
            $cfg['loading'],
            $cfg['class'],
            $fetch
        );

        return $html . "\n</picture>";
    }

    /**
     * Baut das srcset für ein Bild.
     *
     * @param string $name
     * @param string $ratio
     * @param string $format
     *
     * @return string
     */
    private function buildSrcset(
        string $name,
        string $ratio,
        string $format
    ): string {
        $out = [];

        foreach ($this->config['widths'] as $w) {
            $out[] = sprintf(
                '%s/%s-%s-%d.%s %dw',
                $this->config['base_path'],
                $name,
                $ratio,
                $w,
                $format,
                $w
            );
        }

        return implode(', ', $out);
    }
}