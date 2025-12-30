<?php
declare(strict_types=1);

/**
 * Clean Output MVC
 *
 * STDERR Log Target
 *
 * Log-Target zur Ausgabe von Log-Zeilen
 * auf STDERR.
 *
 * Aufgabe:
 * - Schreibt formatierte Log-Zeilen
 *   direkt nach php://stderr
 *
 * ❗ WICHTIG:
 * - KEINE Formatierung
 * - KEINE Filterung
 * - KEINE Persistenz
 *
 * Geeignet für:
 * - CLI
 * - Container-Umgebungen
 * - Debugging
 *
 * @package   CHK\Logging\Target
 * @author    Michael Korte
 * @license   MIT
 */

namespace CHK\Logging\Target;

use CHK\Logging\TargetInterface;

final class StdErrTarget implements TargetInterface
{
    /**
     * Schreibt eine Log-Zeile nach STDERR.
     *
     * @param string $line
     *
     * @return void
     */
    public function write(string $line): void
    {
        file_put_contents('php://stderr', $line);
    }
}