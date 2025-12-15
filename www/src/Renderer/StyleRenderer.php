<?php
/**
 * Clean Output MVC
 *
 * Style Renderer
 *
 * Renders CSS styles inline based on registered style handles.
 * Styles are read from the filesystem, lightly minified,
 * and injected as <style> blocks.
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

final class StyleRenderer
{
    /**
     * Style configuration indexed by handle.
     *
     * @var array<string, array>
     */
    private array $config;

    /**
     * @param array<string, array> $config
     */
    public function __construct(array $config = [])
    {
        $this->config = $config;
    }

    /**
     * Render styles for the given handles.
     *
     * @param string[] $handles
     * @return string Rendered <style> tags
     */
    public function render(array $handles): string
    {
        $output = '';

        foreach ($handles as $handle) {
            if (!isset($this->config[$handle])) {
                continue;
            }

            $definition = $this->config[$handle];
            $src        = $definition['src'] ?? null;

            if (!$src) {
                continue;
            }

            $path = dirname(__DIR__, 2) . '/' . $src;

            if (!is_readable($path)) {
                continue;
            }

            $css = file_get_contents($path);

            // Light minification (whitespace + trivial tokens)
            $css = preg_replace('/\s+/', ' ', $css);
            $css = str_replace(
                [': ', ' {', '{ ', '; }'],
                [':', '{', '{', '}'],
                $css
            );

            $output .= "<style id=\"style-{$handle}\">\n{$css}\n</style>\n";
        }

        return $output;
    }
}