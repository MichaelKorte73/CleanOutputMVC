<?php
declare(strict_types=1);

namespace CHK\Core;

/**
 * Plugins sind Hook-Only-Erweiterungen.
 * Sie liefern keine Routen/Controller/Pages und enthalten keine Domain-Logik.
 *
 * Core kann das nicht technisch erzwingen – das ist Governance/Policy.
 */
interface PluginInterface
{
    public function register(HookManager $hooks, App $app): void;
}