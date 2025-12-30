<?php
declare(strict_types=1);

/**
 * Clean Output MVC
 *
 * Null Logger
 *
 * Null-Object-Implementierung des LoggerInterface.
 *
 * Aufgabe:
 * - Dient als Fallback, wenn Logging deaktiviert ist
 * - Verhindert Null-Checks im Code
 *
 * ❗ WICHTIG:
 * - Schreibt KEINE Logs
 * - Führt KEINE Aktionen aus
 *
 * Wird eingesetzt, um Logging
 * vollständig auszuschalten.
 *
 * @package   CHK\Logging
 * @author    Michael Korte
 * @license   MIT
 */

namespace CHK\Logging;

final class NullLogger implements LoggerInterface
{
    /**
     * Prüft, ob ein Log-Level aktiv ist.
     *
     * @param int $level
     *
     * @return bool
     */
    public function isEnabled(int $level): bool
    {
        return false;
    }

    /**
     * Nimmt Log-Aufrufe entgegen,
     * führt jedoch keine Aktion aus.
     *
     * @param int    $level
     * @param string $scope
     * @param string $origin
     * @param string $message
     * @param array  $context
     *
     * @return void
     */
    public function log(
        int $level,
        string $scope,
        string $origin,
        string $message,
        array $context = []
    ): void {
        // intentionally empty
    }
}