<?php

namespace CHK\Core;

/**
 * Hooks
 *
 * Minimal hook system providing Actions and Filters.
 *
 * Design goals:
 * - Explicit and predictable execution order
 * - No magic, no auto-discovery
 * - Hooks are extension points, not an architecture replacement
 *
 * Important:
 * - Hooks are executed only when explicitly called
 * - There are no early bootstrap hooks
 * - Core logic must never depend on hooks
 */
final class Hooks
{
    /** @var array<string,array<int,callable[]>> */
    protected static array $actions = [];

    /** @var array<string,array<int,callable[]>> */
    protected static array $filters = [];

    // -------------------------------------------------
    // Actions
    // -------------------------------------------------

    /**
     * Register an action callback.
     *
     * @param string   $hook     Hook name
     * @param callable $callback Callback to execute
     * @param int      $priority Execution priority (lower = earlier)
     */
    public static function addAction(
        string $hook,
        callable $callback,
        int $priority = 10
    ): void {
        self::$actions[$hook][$priority][] = $callback;
    }

    /**
     * Execute all callbacks registered for an action hook.
     *
     * @param string $hook Hook name
     * @param mixed  ...$args Arguments passed to callbacks
     */
    public static function doAction(string $hook, ...$args): void
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

    // -------------------------------------------------
    // Filters
    // -------------------------------------------------

    /**
     * Register a filter callback.
     *
     * @param string   $hook     Hook name
     * @param callable $callback Callback that modifies a value
     * @param int      $priority Execution priority (lower = earlier)
     */
    public static function addFilter(
        string $hook,
        callable $callback,
        int $priority = 10
    ): void {
        self::$filters[$hook][$priority][] = $callback;
    }

    /**
     * Apply all filters to a value.
     *
     * @param string $hook Hook name
     * @param mixed  $value Initial value
     * @param mixed  ...$args Additional arguments for filters
     */
    public static function applyFilters(
        string $hook,
        mixed $value,
        ...$args
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