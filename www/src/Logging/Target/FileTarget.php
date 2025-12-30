<?php
declare(strict_types=1);

/**
 * Clean Output MVC
 *
 * File Log Target
 *
 * Log-Target zur Ausgabe von Log-Zeilen
 * in eine Datei.
 *
 * Aufgabe:
 * - Schreibt formatierte Log-Zeilen
 *   in eine definierte Datei
 *
 * ❗ WICHTIG:
 * - KEINE Formatierung
 * - KEINE Log-Level-Logik
 * - KEINE Rotation / Cleanup
 *
 * FileTarget ist bewusst simpel
 * und delegiert alle weitergehenden
 * Anforderungen an die Umgebung.
 *
 * @package   CHK\Logging\Target
 * @author    Michael Korte
 * @license   MIT
 */

namespace CHK\Logging\Target;

use CHK\Logging\TargetInterface;

final class FileTarget implements TargetInterface
{
    /**
     * Ziel-Datei für Log-Ausgaben.
     */
    private string $file;

    public function __construct(string $file)
    {
        $this->file = $file;
    }

    /**
     * Schreibt eine Log-Zeile in die Datei.
     *
     * @param string $line
     *
     * @return void
     */
    public function write(string $line): void
    {
        file_put_contents(
            $this->file,
            $line,
            FILE_APPEND | LOCK_EX
        );
    }
}