<?php
declare(strict_types=1);

/**
 * Clean Output MVC Framework
 *
 * Permission Resolver
 * -------------------
 * Zentrale Policy-Schicht für Capabilities & Permissions.
 *
 * - Core-only (kein User-, Rollen- oder Admin-System)
 * - Default: allow-all (fail open)
 * - Vorbereitung für spätere CMS-/Admin-Layer
 *
 * Philosophie:
 * Capabilities beschreiben *was* ein Component kann.
 * Permissions entscheiden *ob* es im aktuellen Kontext erlaubt ist.
 *
 * ❗ Keine UI-Logik
 * ❗ Keine Authentifizierung
 * ❗ Keine Rollen
 *
 * @package   CHK\Core
 * @author    Michael Korte
 * @license   MIT
 */

namespace CHK\Core;

final class PermissionResolver
{
    /**
     * Explizite Permission-Overrides.
     *
     * key   = capability
     * value = erlaubt (true) / verboten (false)
     *
     * @var array<string,bool>
     */
    private array $overrides = [];

    /**
     * Prüft, ob eine Capability im aktuellen App-Kontext erlaubt ist.
     *
     * Default-Policy (v0.3 Core):
     * - Wenn keine Regel existiert → erlaubt (fail open)
     * - Explizite Overrides schlagen Default
     *
     * @param string $capability
     * @param App    $app        Aktueller App-Kontext (vorbereitet für spätere Erweiterung)
     */
    public function isAllowed(string $capability, App $app): bool
    {
        if (array_key_exists($capability, $this->overrides)) {
            return $this->overrides[$capability];
        }

        return true;
    }

    /**
     * Erlaubt explizit eine Capability.
     */
    public function allow(string $capability): void
    {
        $this->overrides[$capability] = true;
    }

    /**
     * Verbietet explizit eine Capability.
     */
    public function deny(string $capability): void
    {
        $this->overrides[$capability] = false;
    }

    /**
     * Setzt mehrere Permission-Regeln auf einmal.
     *
     * Typischer Einsatz:
     * - App-Bootstrap
     * - Policy-Datei
     * - später: Admin / CMS
     *
     * @param array<string,bool> $rules
     */
    public function apply(array $rules): void
    {
        foreach ($rules as $capability => $allowed) {
            $this->overrides[$capability] = (bool) $allowed;
        }
    }

    /**
     * Liefert alle gesetzten Overrides (Debug / Introspection).
     *
     * @return array<string,bool>
     */
    public function all(): array
    {
        return $this->overrides;
    }
}