<?php
namespace CHK\Core;

use AltoRouter;

final class Router
{
    private AltoRouter $router;

    public function __construct(array $config)
    {
        $this->router = new AltoRouter();

        if (!empty($config['base_path'])) {
            $this->router->setBasePath($config['base_path']);
        }
    }

    public function map(
        string $method,
        string $path,
        array $target,
        ?string $name = null
    ): void {
        $this->router->map($method, $path, $target, $name);
    }

    /**
     * Liefert IMMER ein normiertes Match-Array
     */
    public function match(): array
    {
        $match = $this->router->match();

        if ($match) {
            return [
                'type'   => 'route',
                'target' => $this->normalizeTarget($match['target']),
                'params' => $match['params'] ?? [],
            ];
        }

        return [
            'type' => 'fallback',
            'code' => 404,
        ];
    }

    private function normalizeTarget(mixed $target): array
    {
        if (is_array($target)) {
            return [
                'type'       => 'controller',
                'controller' => $target['controller'] ?? null,
                'action'     => $target['action'] ?? null,
            ];
        }

        throw new \RuntimeException('Invalid route target');
    }
}