<?php
declare(strict_types=1);

namespace CHK\Core;

/**
 * Clean Output MVC
 *
 * Component Contract
 *
 * Components sind fachliche, aktive System-Bausteine.
 * Sie dürfen:
 * - Routen registrieren
 * - Services bereitstellen
 * - Hooks abonnieren
 * - Capabilities deklarieren
 *
 * ❗ Capabilities sind rein deklarativ:
 * - KEINE Durchsetzung
 * - KEINE Security- oder Permission-Logik
 * - KEINE Aussage darüber, *wer* etwas darf
 *
 * Sie beschreiben ausschließlich:
 * „Diese Component ist technisch in der Lage, X bereitzustellen.“
 *
 * Core-Policy:
 * - Keine Auto-Discovery
 * - Registrierung erfolgt explizit über App::addComponent()
 *
 * @package   CHK\Core
 * @author    Michael Korte
 * @license   MIT
 */
interface ComponentInterface
{
    /**
     * Registrierung der Component im System.
     *
     * Typische Aufgaben:
     * - Routen registrieren
     * - Hooks abonnieren
     * - Services im App-Container ablegen
     * - Capabilities via App::registerCapability() melden
     *
     * Wird genau einmal pro Request aufgerufen.
     */
    public function register(App $app): void;

    /**
     * Deklariert die von dieser Component bereitgestellten Capabilities.
     *
     * Diese Methode:
     * - deklariert POTENZIAL
     * - erzwingt NICHTS
     * - trifft KEINE Zugriffsentscheidung
     *
     * Beispiele:
     * [
     *   'admin',
     *   'media.read',
     *   'media.write',
     *   'db',
     *   'filesystem',
     * ]
     *
     * @return string[] Liste eindeutiger Capability-Namen
     */
    public function capabilities(): array;
}