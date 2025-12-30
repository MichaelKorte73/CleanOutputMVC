<?php
declare(strict_types=1);

/**
 * Clean Output MVC
 *
 * Script Renderer
 *
 * Renderer-Komponente zur serverseitigen Ausgabe
 * von JavaScript-Ressourcen.
 *
 * Aufgabe:
 * - Rendert Scripts anhand von Handles und Gruppen
 * - Unterstützt Inline- und externe Scripts
 * - Stellt definierte Ausgabereihenfolge sicher
 *
 * ❗ WICHTIG:
 * - KEIN Dependency-Resolver
 * - KEIN Bundler
 * - KEIN Cache / Versioning
 *
 * ScriptRenderer ist bewusst simpel und
 * deterministisch.
 *
 * @package   CHK\Renderer
 * @author    Michael Korte
 * @license   MIT
 */

namespace CHK\Renderer;

final class ScriptRenderer
{
    /**
     * Script-Konfiguration (Handle → Definition).
     *
     * @var array<string,array>
     */
    private array $config;

    public function __construct(array $config = [])
    {
        $this->config = $config;
    }

    /**
     * Rendert eine Script-Gruppe.
     *
     * Gruppen:
     * - top
     * - head
     * - footer
     *
     * @param string   $group    Zielgruppe der Ausgabe
     * @param string[] $handles  Script-Handles
     *
     * @return string
     */
    public function renderGroup(string $group, array $handles = []): string
    {
        if (empty($handles) || empty($this->config)) {
            return '';
        }

        $out = '';

        // Core-Script immer zuerst (falls vorhanden)
        if (in_array('core', $handles, true)) {
            $handles = array_values(
                array_unique(array_merge(['core'], $handles))
            );
        }

        foreach ($handles as $handle) {
            if (!isset($this->config[$handle])) {
                continue;
            }

            $def = $this->config[$handle];

            if (($def['group'] ?? 'footer') !== $group) {
                continue;
            }

            // ----------------------------
            // INLINE SCRIPT
            // ----------------------------
            if (($def['type'] ?? 'inline') === 'inline') {
                $path = dirname(__DIR__, 2) . '/' . $def['src'];

                if (!is_readable($path)) {
                    continue;
                }

                $content = file_get_contents($path);
                if ($content === false) {
                    continue;
                }

                $out .= "<script>\n{$content}\n</script>\n";
                continue;
            }

            // ----------------------------
            // EXTERNAL SCRIPT
            // ----------------------------
            if ($def['type'] === 'external') {
                $attrs = '';

                foreach ($def['attrs'] ?? [] as $k => $v) {
                    $attrs .= is_bool($v)
                        ? " {$k}"
                        : ' ' . $k . '="' . htmlspecialchars(
                            (string) $v,
                            ENT_QUOTES,
                            'UTF-8'
                        ) . '"';
                }

                $out .= sprintf(
                    "<script src=\"%s\"%s></script>\n",
                    htmlspecialchars($def['src'], ENT_QUOTES, 'UTF-8'),
                    $attrs
                );
            }
        }

        return $out;
    }
}