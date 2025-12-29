<?php
declare(strict_types=1);

namespace CHK\Core;

/**
 * Components liefern fachliche Capabilities.
 *
 * Capabilities sind deklarativ:
 * - keine Durchsetzung
 * - keine Security-Logik
 * - nur Beschreibung dessen, was eine Component POTENZIELL kann
 *
 * Core-Policy:
 * - Keine Auto-Discovery
 * - Registrierung erfolgt explizit über App::addComponent()
 */
interface ComponentInterface
{
    /**
     * Registrierung der Component:
     * - Routen
     * - Hooks
     * - Services
     */
    public function register(App $app): void;

    /**
     * Deklaration der Capabilities dieser Component.
     *
     * Beispiele:
     * [
     *   'admin',
     *   'media.read',
     *   'media.write',
     *   'db',
     *   'filesystem',
     * ]
     */
    public function capabilities(): array;
}