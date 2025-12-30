<?php
declare(strict_types=1);

/**
 * Clean Output MVC
 *
 * Log Target Interface
 *
 * Vertrag für Log-Ziele (Targets).
 *
 * Aufgabe:
 * - Nimmt bereits formatierte Log-Zeilen entgegen
 * - Schreibt diese an ein Ziel (File, Stream, etc.)
 *
 * ❗ WICHTIG:
 * - KEINE Formatierung
 * - KEINE Log-Level-Logik
 * - KEINE Filterung
 *
 * TargetInterface ist der letzte Schritt
 * in der Logging-Pipeline.
 *
 * @package   CHK\Logging
 * @author    Michael Korte
 * @license   MIT
 */

namespace CHK\Logging;

interface TargetInterface
{
    /**
     * Schreibt eine formatierte Log-Zeile.
     *
     * @param string $line
     *
     * @return void
     */
    public function write(string $line): void;
}