<?php
declare(strict_types=1);

/**
 * Clean Output MVC
 *
 * Router
 *
 * Dünner Wrapper um AltoRouter.
 * Liefert ein strikt normiertes Match-Array,
 * inklusive vorbereiteter Route-Metadaten.
 *
 * ❗ Keine Logik im Router
 * ❗ Keine Capabilities-Prüfung
 * ❗ Nur Struktur & Normalisierung
 *
 * @package   CHK\Core
 * @author    Michael Korte
 * @license   MIT
 */

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

    /**
     * Registriert eine Route.
     *
     * Erwartetes Target-Schema:
     * [
     *   'controller'   => FQCN,
     *   'action'       => 'index',
     *   'capabilities' => ['media.read'],
     *   'area'         => 'admin'|'frontend',
     * ]
     */
    public function map(
        string $method,
        string $path,
        array $target,
        ?string $name = null
    ): void {
        $this->router->map($method, $path, $target, $name);
    }

    /**
     * Liefert IMMER ein normiertes Match-Array.
     */
    public function match(): array
    {
        $match = $this->router->match();

        if ($match) {
            return [
                'type'   => 'route',
                'route'  => $this->normalizeRoute(
                    $match['target'],
                    $match['name'] ?? null
                ),
                'params' => $match['params'] ?? [],
            ];
        }

        return [
            'type' => 'fallback',
            'code' => 404,
        ];
    }

    /**
     * Normalisiert das Route-Target in eine feste Struktur.
     */
    private function normalizeRoute(mixed $target, ?string $name): array
    {
        if (!is_array($target)) {
            throw new \RuntimeException('Invalid route target');
        }

        return [
            'controller'   => $target['controller'] ?? null,
            'action'       => $target['action'] ?? 'index',
            'capabilities' => $target['capabilities'] ?? null,
            'area'         => $target['area'] ?? 'frontend',
            'name'         => $name,
        ];
    }
}