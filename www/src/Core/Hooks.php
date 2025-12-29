<?php
declare(strict_types=1);

/**
 * Clean Output MVC
 *
 * Hooks
 *
 * Statisches Hook-System für Actions & Filters.
 *
 * ⚠️ Architektur-Entscheidung:
 * - Hooks sind bewusst GLOBAL & statisch
 * - Keine Dependency Injection
 * - Kein Lifecycle-Magic
 *
 * Begründung:
 * - deterministische Ausführung
 * - minimale Laufzeitkosten
 * - klare Debugbarkeit
 *
 * Nutzung:
 * - Core feuert definierte Hooks (z. B. app.ready, renderer.before)
 * - Plugins registrieren Callbacks
 * - Components registrieren sich NICHT automatisch
 *
 * ❌ Keine Bootstrap-Hooks
 * ❌ Keine Router-Hooks
 * ❌ Keine versteckten Hooks
 *
 * @package   CHK\Core
 * @author    Michael Korte
 * @license   MIT
 */
namespace CHK\Core;

final class Hooks
{
    /**
     * @var array<string, array<int, callable[]>>
     */
    protected static array $actions = [];

    /**
     * @var array<string, array<int, callable[]>>
     */
    protected static array $filters = [];

    /* ----------------------------------------
       ACTIONS
    ---------------------------------------- */

    /**
     * Registriert eine Action.
     *
     * @param string   $hook     Hook-Name
     * @param callable $callback Callback
     * @param int      $priority Ausführungsreihenfolge (niedrig = früher)
     */
    public static function addAction(
        string $hook,
        callable $callback,
        int $priority = 10
    ): void {
        self::$actions[$hook][$priority][] = $callback;
    }

    /**
     * Führt alle Actions eines Hooks aus.
     *
     * @param string $hook
     * @param mixed  ...$args
     */
    public static function doAction(string $hook, mixed ...$args): void
    {
        if (!isset(self::$actions[$hook])) {
            return;
        }

        ksort(self::$actions[$hook]);

        foreach (self::$actions[$hook] as $callbacks) {
            foreach ($callbacks as $callback) {
                $callback(...$args);
            }
        }
    }

    /* ----------------------------------------
       FILTERS
    ---------------------------------------- */

    /**
     * Registriert einen Filter.
     *
     * @param string   $hook
     * @param callable $callback
     * @param int      $priority
     */
    public static function addFilter(
        string $hook,
        callable $callback,
        int $priority = 10
    ): void {
        self::$filters[$hook][$priority][] = $callback;
    }

    /**
     * Wendet Filter auf einen Wert an.
     *
     * @param string $hook
     * @param mixed  $value
     * @param mixed  ...$args
     */
    public static function applyFilters(
        string $hook,
        mixed $value,
        mixed ...$args
    ): mixed {
        if (!isset(self::$filters[$hook])) {
            return $value;
        }

        ksort(self::$filters[$hook]);

        foreach (self::$filters[$hook] as $callbacks) {
            foreach ($callbacks as $callback) {
                $value = $callback($value, ...$args);
            }
        }

        return $value;
    }
}