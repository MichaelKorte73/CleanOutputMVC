<?php
/**
 * Clean Output MVC
 *
 * Image Renderer
 *
 * Renders responsive <picture> elements based on image presets.
 * Supports multiple formats, srcset generation and semantic defaults.
 *
 * This renderer is intentionally dumb:
 * - no filesystem checks
 * - no image generation
 * - no magic fallbacks
 *
 * It assumes that all assets already exist.
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

final class ImageRenderer
{
    /**
     * Image configuration.
     *
     * @var array
     */
    private array $config;

    /**
     * @param array|null $imageConfig Image configuration array
     */
    public function __construct(array $imageConfig = null)
    {
        $this->config = $imageConfig ?? [];
    }

    /**
     * Render a responsive image.
     *
     * @param string $preset    Preset name (e.g. hero, content)
     * @param string $name      Base image filename (without size / extension)
     * @param string $alt       Alt text
     * @param array  $overrides Optional preset overrides
     *
     * @return string Rendered <picture> HTML
     */
    public function render(
        string $preset,
        string $name,
        string $alt = '',
        array $overrides = []
    ): string {
        if (!isset($this->config['presets'][$preset])) {
            throw new \InvalidArgumentException(
                "Unknown image preset: {$preset}"
            );
        }

        $config = array_merge(
            $this->config['presets'][$preset],
            $overrides
        );

        $html = "<picture>\n";

        // -------------------------------------------------
        // Source elements (formats & srcsets)
        // -------------------------------------------------
        foreach ($this->config['formats'] as $format) {
            $html .= sprintf(
                '<source type="image/%s" srcset="%s" sizes="%s">' . "\n",
                $format,
                $this->buildSrcset($name, $config['ratio'], $format),
                $config['sizes']
            );
        }

        // -------------------------------------------------
        // Fallback image
        // -------------------------------------------------
        $fallback = sprintf(
            '%s/%s-%s-%d.webp',
            $this->config['base_path'],
            $name,
            $config['ratio'],
            $config['fallbackWidth']
        );

        $fetchPriority = isset($config['fetchpriority'])
            ? ' fetchpriority="' . $config['fetchpriority'] . '"'
            : '';

        $html .= sprintf(
            '<img src="%s" alt="%s" loading="%s" decoding="async" class="%s"%s>',
            $fallback,
            htmlspecialchars($alt, ENT_QUOTES, 'UTF-8'),
            $config['loading'],
            $config['class'],
            $fetchPriority
        );

        return $html . "\n</picture>";
    }

    /**
     * Build a srcset string for a given image.
     *
     * @param string $name   Base image filename
     * @param string $ratio  Aspect ratio identifier
     * @param string $format Image format (e.g. webp)
     *
     * @return string Srcset attribute value
     */
    private function buildSrcset(
        string $name,
        string $ratio,
        string $format
    ): string {
        $entries = [];

        foreach ($this->config['widths'] as $width) {
            $entries[] = sprintf(
                '%s/%s-%s-%d.%s %dw',
                $this->config['base_path'],
                $name,
                $ratio,
                $width,
                $format,
                $width
            );
        }

        return implode(', ', $entries);
    }
}