<?php
/**
 * Clean Output MVC
 *
 * Script Renderer
 *
 * Renders JavaScript assets based on registered handles and groups.
 * Supports inline scripts (filesystem) and external scripts.
 * Ensures that the core script is always rendered first if present.
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

final class ScriptRenderer
{
    /**
     * Script configuration indexed by handle.
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
     * Render scripts for a given group.
     *
     * @param string   $group   Script group (top|head|footer)
     * @param string[] $handles Script handles
     *
     * @return string Rendered <script> tags
     */
    public function renderGroup(string $group, array $handles = []): string
    {
        if (empty($handles) || empty($this->config)) {
            return '';
        }

        $output = '';

        // Ensure core is always rendered first (if present)
        if (in_array('core', $handles, true)) {
            $handles = array_values(
                array_unique(
                    array_merge(['core'], $handles)
                )
            );
        }

        foreach ($handles as $handle) {
            if (!isset($this->config[$handle])) {
                continue;
            }

            $definition = $this->config[$handle];

            if (($definition['group'] ?? 'footer') !== $group) {
                continue;
            }

            // ----------------------------
            // Inline script
            // ----------------------------
            if (($definition['type'] ?? 'inline') === 'inline') {
                $path = dirname(__DIR__, 2) . '/' . $definition['src'];

                if (!is_readable($path)) {
                    continue;
                }

                $output .= "<script>\n"
                    . file_get_contents($path)
                    . "\n</script>\n";

                continue;
            }

            // ----------------------------
            // External script
            // ----------------------------
            if ($definition['type'] === 'external') {
                $attributes = '';

                foreach ($definition['attrs'] ?? [] as $key => $value) {
                    $attributes .= is_bool($value)
                        ? " {$key}"
                        : " {$key}=\"" . htmlspecialchars($value, ENT_QUOTES, 'UTF-8') . "\"";
                }

                $output .= sprintf(
                    '<script src="%s"%s></script>' . "\n",
                    htmlspecialchars($definition['src'], ENT_QUOTES, 'UTF-8'),
                    $attributes
                );
            }
        }

        return $output;
    }
}