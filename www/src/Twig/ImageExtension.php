<?php
/**
 * Clean Output MVC
 *
 * Twig Image Extension
 *
 * Provides a unified Twig function to render responsive <picture> markup
 * based on predefined image presets.
 *
 * This extension deliberately contains no business logic and no file-system
 * access. It only transforms configuration into semantic HTML output.
 *
 * @author    Michael Korte
 * @email     mkorte@korte-software.de
 * @company   Michael Korte Software
 *
 * @version   0.1
 * @date      13.12.2025
 *
 * @package   CHK\Twig
 */

namespace CHK\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

final class ImageExtension extends AbstractExtension
{
    /**
     * Image configuration array.
     *
     * @var array<string, mixed>
     */
    private array $config;

    /**
     * @param array<string, mixed> $config Image configuration
     */
    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * Register Twig functions.
     *
     * @return TwigFunction[]
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('image', [$this, 'renderImage'], ['is_safe' => ['html']]),
            new TwigFunction('image_config', [$this, 'getPreset']),
        ];
    }

    /**
     * Return configuration for a given image preset.
     *
     * @param string $preset Preset name
     *
     * @return array<string, mixed>
     *
     * @throws \InvalidArgumentException If preset does not exist
     */
    public function getPreset(string $preset): array
    {
        if (!isset($this->config['presets'][$preset])) {
            throw new \InvalidArgumentException("Unknown image preset: {$preset}");
        }

        return $this->config['presets'][$preset];
    }

    /**
     * Render responsive <picture> markup for an image.
     *
     * @param string $preset Image preset name
     * @param string $file   Base image filename (without size/format)
     * @param string $alt    Alternative text
     *
     * @return string Rendered HTML markup
     */
    public function renderImage(
        string $preset,
        string $file,
        string $alt = ''
    ): string {
        $presetConfig = $this->getPreset($preset);

        $basePath = rtrim($this->config['base_path'], '/');
        $widths   = $this->config['widths'];
        $format   = $this->config['formats'][0]; // e.g. webp

        $srcset = [];
        foreach ($widths as $w) {
            $srcset[] = "{$basePath}/{$file}-{$presetConfig['ratio']}-{$w}.{$format} {$w}w";
        }

        $fallback = "{$basePath}/{$file}-{$presetConfig['ratio']}-{$presetConfig['fallbackWidth']}.{$format}";

        $sizes   = $presetConfig['sizes'];
        $loading = $presetConfig['loading'] ?? 'lazy';
        $fetch   = $presetConfig['fetchpriority'] ?? 'auto';
        $class   = $presetConfig['class'] ?? '';

        return <<<HTML
<picture>
    <source
        type="image/{$format}"
        srcset="{$this->esc(implode(', ', $srcset))}"
        sizes="{$this->esc($sizes)}"
    >
    <img
        src="{$fallback}"
        alt="{$this->esc($alt)}"
        loading="{$loading}"
        decoding="async"
        fetchpriority="{$fetch}"
        class="{$class}"
    >
</picture>
HTML;
    }

    /**
     * Escape HTML output.
     *
     * @param string $value Raw value
     *
     * @return string Escaped value
     */
    private function esc(string $value): string
    {
        return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    }
}