<?php
declare(strict_types=1);

/**
 * Clean Output MVC
 *
 * Image Twig Extension
 *
 * Twig-Extension zur komfortablen Ausgabe
 * responsiver Bilder in Templates.
 *
 * Aufgabe:
 * - Stellt Twig-Funktionen für Images bereit
 * - Mappt Presets auf HTML-Ausgabe
 *
 * ❗ WICHTIG:
 * - KEIN Rendering im Core-Sinn
 * - KEINE Bildgenerierung
 * - KEINE Dateisystem-Zugriffe
 *
 * Diese Extension ist reines
 * Template-Glue.
 *
 * @package   CHK\Twig
 * @author    Michael Korte
 * @license   MIT
 */

namespace CHK\Twig;

use InvalidArgumentException;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

final class ImageExtension extends AbstractExtension
{
    /**
     * Image-Konfiguration.
     *
     * @var array
     */
    private array $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction(
                'image',
                [$this, 'renderImage'],
                ['is_safe' => ['html']]
            ),
            new TwigFunction(
                'image_config',
                [$this, 'getPreset']
            ),
        ];
    }

    /**
     * Liefert die Konfiguration eines Presets.
     *
     * @param string $preset
     *
     * @return array
     */
    public function getPreset(string $preset): array
    {
        if (!isset($this->config['presets'][$preset])) {
            throw new InvalidArgumentException(
                "Unknown image preset: {$preset}"
            );
        }

        return $this->config['presets'][$preset];
    }

    /**
     * Rendert ein <picture>-Element für ein Image.
     *
     * @param string $preset
     * @param string $file
     * @param string $alt
     *
     * @return string
     */
    public function renderImage(
        string $preset,
        string $file,
        string $alt = ''
    ): string {
        $presetConfig = $this->getPreset($preset);

        $basePath = rtrim($this->config['base_path'], '/');
        $widths   = $this->config['widths'];
        $format   = $this->config['formats'][0]; // z. B. webp

        $srcset = [];
        foreach ($widths as $w) {
            $srcset[] =
                "{$basePath}/{$file}-{$presetConfig['ratio']}-{$w}.{$format} {$w}w";
        }

        $fallback =
            "{$basePath}/{$file}-{$presetConfig['ratio']}-{$presetConfig['fallbackWidth']}.{$format}";

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

    private function esc(string $value): string
    {
        return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    }
}