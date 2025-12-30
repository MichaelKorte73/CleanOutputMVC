<?php
declare(strict_types=1);

/**
 * Clean Output MVC
 *
 * Route Provider Interface
 *
 * Vertrag für Klassen, die Routen am Core-Router registrieren.
 *
 * Typische Implementierungen:
 * - AppRoutes (Projekt-spezifische Routen)
 * - ComponentRoutes (fachliche Teilbereiche)
 *
 * Verantwortung:
 * - Registrierung von HTTP-Routen
 * - Definition von Controller, Action und Metadaten
 *
 * ❗ KEINE Request-Logik
 * ❗ KEINE Middleware
 * ❗ KEINE Capabilities-Prüfung
 *
 * Der Router selbst bleibt dumm – alle Entscheidungen
 * passieren auf höherer Ebene (Middleware / Controller).
 *
 * @package   CHK\Core
 * @author    Michael Korte
 * @license   MIT
 */

namespace CHK\Core;

interface RouteProviderInterface
{
    /**
     * Registriert Routen am Core-Router.
     *
     * Implementierungen dürfen:
     * - beliebige HTTP-Methoden verwenden
     * - Route-Metadaten definieren (z. B. capabilities, area)
     *
     * Erwartetes Target-Schema (Beispiel):
     * [
     *   'controller'   => Controller::class,
     *   'action'       => 'index',
     *   'capabilities' => ['media.read'],
     *   'area'         => 'admin',
     * ]
     *
     * @param Router $router  Core-Router
     */
    public function registerRoutes(Router $router): void;
}