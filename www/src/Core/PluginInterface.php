<?php
declare(strict_types=1);

/**
 * Clean Output MVC
 *
 * Plugin Interface
 *
 * Plugins sind reine Hook-Erweiterungen des Systems.
 *
 * Charakteristik:
 * - ❗ KEINE Routen
 * - ❗ KEINE Controller
 * - ❗ KEINE Page-Erzeugung
 * - ❗ KEINE Domain-Logik
 *
 * Plugins dürfen:
 * - Hooks abonnieren (Actions & Filters)
 * - Verhalten bestehender Abläufe beeinflussen
 * - Services lesen (nicht orchestrieren)
 *
 * Plugins dürfen NICHT:
 * - eigene Requests bedienen
 * - Routing beeinflussen
 * - Rendering initiieren
 *
 * ⚠️ Wichtig:
 * Diese Regeln sind Governance.
 * Der Core erzwingt sie absichtlich NICHT technisch.
 *
 * Plugins sind:
 * - optionale Erweiterungen
 * - austauschbar
 * - deaktivierbar
 *
 * Registrierung:
 * - erfolgt explizit im Bootstrap
 * - kein Auto-Discovery
 *
 * @package   CHK\Core
 * @author    Michael Korte
 * @license   MIT
 */

namespace CHK\Core;

interface PluginInterface
{
    /**
     * Registrierung des Plugins.
     *
     * @param HookManager $hooks Zugriff auf das Hook-System
     * @param App         $app   Read-only Zugriff auf den App-Kontext
     */
    public function register(HookManager $hooks, App $app): void;
}