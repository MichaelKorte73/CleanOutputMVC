<?php 

namespace CHK\Renderer;

final class StyleRenderer
{
    private array $config;

    public function __construct(array $config = [])
    {
        $this->config = $config;
    }

    public function render(array $handles): string
    {
        $out = '';
$css="";
        foreach ($handles as $handle) {
            if (!isset($this->config[$handle])) {
                continue;
            }

            $def = $this->config[$handle];
            $src = $def['src'] ?? null;

            if (!$src) {
                continue;
            }

            $path = dirname(__DIR__, 2) ."/". $src;

            if (!is_readable($path)) {
                continue;
            }

            $css = file_get_contents($path);

            // leichte Minifizierung
            $css = preg_replace('/\s+/', ' ', $css);
            $css = str_replace([': ', ' {', '{ ', '; }'], [':', '{', '{', '}'], $css);

            $out .= "<style id=\"style-{$handle}\">\n{$css}\n</style>\n";
        }

;
        return $out;
    }
}