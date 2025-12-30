<?php
declare(strict_types=1);

/**
 * Clean Output MVC
 *
 * Style Renderer
 *
 * Renderer-Komponente zur serverseitigen Ausgabe
 * von CSS-Styles als Inline-Styles.
 *
 * Aufgabe:
 * - Rendert konfigurierte Styles anhand von Handles
 * - Liest CSS-Dateien aus dem Projekt
 * - Gibt diese als <style>-Blöcke aus
 *
 * ❗ WICHTIG:
 * - KEIN Asset-Management
 * - KEINE Dependency-Resolution
 * - KEIN Cache / Versioning
 *
 * Dieser Renderer ist bewusst simpel und
 * dient primär für kontrollierte Inline-Ausgabe.
 *
 * @package   CHK\Renderer
 * @author    Michael Korte
 * @license   MIT
 */

namespace CHK\Renderer;

final class StyleRenderer
{
    /**
     * Style-Konfiguration (Handle → Definition).
     *
     * @var array
     */
    private array $config;

    public function __construct(array $config = [])
    {
        $this->config = $config;
    }

    /**
     * Rendert Styles für die angegebenen Handles.
     *
     * @param string[] $handles
     *
     * @return string
     */
    public function render(array $handles): string
    {
        $out = '';

        foreach ($handles as $handle) {
            if (!isset($this->config[$handle])) {
                continue;
            }

            $def = $this->config[$handle];
            $src = $def['src'] ?? null;

            if (!$src) {
                continue;
            }

            $path = dirname(__DIR__, 2) . '/' . $src;

            if (!is_readable($path)) {
                continue;
            }

            $css = file_get_contents($path);
            if ($css === false) {
                continue;
            }

            // leichte, bewusst naive Minifizierung
            $css = preg_replace('/\s+/', ' ', $css);
            $css = str_replace([': ', ' {', '{ ', '; }'], [':', '{', '{', '}'], $css);

            $out .= "<style id=\"style-{$handle}\">\n{$css}\n</style>\n";
        }

        return $out;
    }
}