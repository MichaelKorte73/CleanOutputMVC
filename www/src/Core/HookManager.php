<?php
declare(strict_types=1);

/**
 * Clean Output MVC
 *
 * Hook Manager
 *
 * Dünner Objekt-Wrapper um die statische Hooks-API.
 *
 * Ziel:
 * - Vermeidung direkter Static-Nutzung in Components & Plugins
 * - Bessere Testbarkeit
 * - Klare Übergabe über App (Dependency Context)
 *
 * Der HookManager selbst enthält:
 * ❗ KEINE Logik
 * ❗ KEINE State-Verwaltung
 * ❗ KEINE Lifecycle-Entscheidungen
 *
 * Er delegiert vollständig an {@see Hooks}.
 *
 * Einsatz:
 * - Wird von der App erzeugt
 * - Wird an Plugins über register(...) übergeben
 * - Kann von Components indirekt genutzt werden
 *
 * @package   CHK\Core
 * @author    Michael Korte
 * @license   MIT
 */

namespace CHK\Core;

final class HookManager
{
    /**
     * Registriert eine Action.
     *
     * Actions sind Fire-and-Forget-Hooks:
     * - kein Rückgabewert
     * - beliebig viele Listener
     *
     * @param string   $hook     Hook-Name
     * @param callable $callback Callback
     * @param int      $priority Ausführungsreihenfolge (niedrig = früher)
     */
    public function addAction(string $hook, callable $callback, int $priority = 10): void
    {
        Hooks::addAction($hook, $callback, $priority);
    }

    /**
     * Führt eine Action aus.
     *
     * Alle registrierten Callbacks werden
     * in Prioritäts-Reihenfolge ausgeführt.
     *
     * @param string $hook Hook-Name
     * @param mixed  ...$args Übergabeparameter
     */
    public function doAction(string $hook, mixed ...$args): void
    {
        Hooks::doAction($hook, ...$args);
    }

    /**
     * Registriert einen Filter.
     *
     * Filter transformieren einen Wert.
     *
     * @param string   $hook     Hook-Name
     * @param callable $callback Callback
     * @param int      $priority Ausführungsreihenfolge
     */
    public function addFilter(string $hook, callable $callback, int $priority = 10): void
    {
        Hooks::addFilter($hook, $callback, $priority);
    }

    /**
     * Wendet alle Filter auf einen Wert an.
     *
     * @param string $hook  Hook-Name
     * @param mixed  $value Ursprungswert
     * @param mixed  ...$args Zusatzparameter
     *
     * @return mixed Gefilterter Wert
     */
    public function applyFilters(string $hook, mixed $value, mixed ...$args): mixed
    {
        return Hooks::applyFilters($hook, $value, ...$args);
    }
}