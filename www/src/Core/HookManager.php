<?php
declare(strict_types=1);

namespace CHK\Core;

/**
 * Dünner Wrapper um die statischen Hooks.
 * Ziel: Plugins/Components bekommen ein Objekt (besser testbar, weniger Static-Leak).
 */
final class HookManager
{
    public function addAction(string $hook, callable $callback, int $priority = 10): void
    {
        Hooks::addAction($hook, $callback, $priority);
    }

    public function doAction(string $hook, mixed ...$args): void
    {
        Hooks::doAction($hook, ...$args);
    }

    public function addFilter(string $hook, callable $callback, int $priority = 10): void
    {
        Hooks::addFilter($hook, $callback, $priority);
    }

    public function applyFilters(string $hook, mixed $value, mixed ...$args): mixed
    {
        return Hooks::applyFilters($hook, $value, ...$args);
    }
}