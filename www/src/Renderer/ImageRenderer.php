<?php
namespace CHK\Renderer;

final class ImageRenderer
{
    private array $config;

    public function __construct(array $imageConfig = null)
    { 
        $this->config = ($imageConfig ===null)?[]:$imageConfig;
    }

    public function render(
        string $preset,
        string $name,
        string $alt = '',
        array $overrides = []
    ): string {

        if (!isset($this->config['presets'][$preset])) {
            throw new \InvalidArgumentException("Unknown image preset: $preset");
        }

        $cfg = array_merge(
            $this->config['presets'][$preset],
            $overrides
        );

        $html = "<picture>\n";

        foreach ($this->config['formats'] as $format) {
            $html .= sprintf(
                '<source type="image/%s" srcset="%s" sizes="%s">' . "\n",
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
            ? ' fetchpriority="'.$cfg['fetchpriority'].'"' : '';

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

    private function buildSrcset(string $name, string $ratio, string $format): string
    {
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