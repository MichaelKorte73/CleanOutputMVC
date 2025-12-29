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
 * Philosophy:
 * Capabilities beschreiben *was* ein Component kann.
 * Permissions entscheiden *ob* es aktuell erlaubt ist.
 *
 * @author  Michael Korte
 * @license MIT
 */

namespace CHK\Core;

final class PermissionResolver
{
    /**
     * @var array<string, bool>
     * explizit gesetzte Permission-Overrides
     */
    private array $overrides = [];

    /**
     * Prüft, ob eine Capability erlaubt ist.
     *
     * Default:
     * - Wenn keine Regel existiert → erlaubt
     * - Explizite Overrides schlagen Default
     *
     * Beispiel-Capability:
     *   "media.read"
     *   "pages.write"
     *   "admin.access"
     */
    public function allows(string $capability): bool
    {
        if (array_key_exists($capability, $this->overrides)) {
            return $this->overrides[$capability];
        }

        // Core-Policy v0.3: alles erlaubt, solange nichts verbietet
        return true;
    }

    /**
     * Explizites Erlauben einer Capability
     */
    public function allow(string $capability): void
    {
        $this->overrides[$capability] = true;
    }

    /**
     * Explizites Verbieten einer Capability
     */
    public function deny(string $capability): void
    {
        $this->overrides[$capability] = false;
    }

    /**
     * Bulk-Definition (z.B. aus App / CMS / Policy-Datei)
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
     * Debug / Introspection
     */
    public function all(): array
    {
        return $this->overrides;
    }
}