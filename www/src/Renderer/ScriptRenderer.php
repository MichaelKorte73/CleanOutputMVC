<?php
namespace CHK\Renderer;

final class ScriptRenderer
{
    /** @var array<string,array> */
    private array $config;

    public function __construct(array $config = [])
    {
        $this->config = $config;
    }

    /**
     * @param string   $group   top|head|footer
     * @param string[] $handles
     */
    public function renderGroup(string $group, array $handles = []): string
    {

        if (empty($handles) || empty($this->config)) {
            return '';
        }

        $out = '';

        // core immer zuerst (falls vorhanden)
        if (in_array('core', $handles, true)) {
            $handles = array_values(array_unique(array_merge(['core'], $handles)));
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
                $path = dirname(__DIR__, 2) . '/'. $def['src'];
                if (!is_readable($path)) {
                    continue;
                }

                $out .= "<script>\n"
                     . file_get_contents($path)
                     . "\n</script>\n";

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
                        : " {$k}=\"" . htmlspecialchars($v, ENT_QUOTES, 'UTF-8') . "\"";
                }

                $out .= sprintf(
                    '<script src="%s"%s></script>' . "\n",
                    htmlspecialchars($def['src'], ENT_QUOTES, 'UTF-8'),
                    $attrs
                );
            }
        }

        return $out;
    }
}