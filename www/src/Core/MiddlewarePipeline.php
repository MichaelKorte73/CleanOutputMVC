<?php
declare(strict_types=1);

/**
 * Clean Output MVC
 *
 * Middleware Pipeline
 *
 * Führt eine Kette von Middleware-Instanzen aus
 * und übergibt einen gemeinsamen Request-Kontext.
 *
 * Eigenschaften:
 * - Reihenfolge entspricht der Registrierungsreihenfolge
 * - Jede Middleware entscheidet selbst, ob sie weiterleitet
 * - Keine Core-Logik, kein Wissen über Capabilities, Auth, etc.
 *
 * Technisches Prinzip:
 * MiddlewareA → MiddlewareB → Controller → Response
 *
 * @package   CHK\Core
 * @author    Michael Korte
 * @license   MIT
 */

namespace CHK\Core;

final class MiddlewarePipeline
{
    /**
     * Stack registrierter Middleware.
     *
     * Die Middleware wird in der Reihenfolge ausgeführt,
     * in der sie hinzugefügt wurde.
     *
     * @var MiddlewareInterface[]
     */
    private array $stack = [];

    /**
     * Fügt eine Middleware zur Pipeline hinzu.
     *
     * Die Reihenfolge der Registrierung bestimmt
     * die Ausführungsreihenfolge.
     */
    public function add(MiddlewareInterface $middleware): void
    {
        $this->stack[] = $middleware;
    }

    /**
     * Führt die Middleware-Pipeline aus.
     *
     * @param array    $context      Gemeinsamer Request-Kontext
     *                               (z. B. app, route, params, request)
     * @param callable $destination  Finaler Handler (i. d. R. Controller)
     *
     * @return mixed                 Rückgabewert der letzten Middleware
     *                               oder des Controllers
     */
    public function handle(array $context, callable $destination): mixed
    {
        $pipeline = array_reduce(
            array_reverse($this->stack),
            fn ($next, MiddlewareInterface $middleware) =>
                fn ($ctx) => $middleware->handle($ctx, $next),
            $destination
        );

        return $pipeline($context);
    }
}