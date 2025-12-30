<?php
declare(strict_types=1);

/**
 * Clean Output MVC
 *
 * Logger Interface
 *
 * Vertrag für Logger-Implementierungen
 * im Framework.
 *
 * Aufgabe:
 * - Definiert die minimale Logger-API
 * - Ermöglicht austauschbare Logger-Backends
 *
 * ❗ WICHTIG:
 * - KEINE Implementierungsdetails
 * - KEINE Formatierungs- oder Persistenzlogik
 *
 * Wird vom Core und von Components
 * zur strukturierten Protokollierung genutzt.
 *
 * @package   CHK\Logging
 * @author    Michael Korte
 * @license   MIT
 */

namespace CHK\Logging;

interface LoggerInterface
{
    /**
     * Schreibt einen Log-Eintrag.
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
    ): void;

    /**
     * Prüft, ob ein Log-Level aktiv ist.
     *
     * @param int $level
     *
     * @return bool
     */
    public function isEnabled(int $level): bool;
}