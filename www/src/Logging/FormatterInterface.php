<?php
declare(strict_types=1);

/**
 * Clean Output MVC
 *
 * Log Formatter Interface
 *
 * Vertrag für Log-Formatter.
 *
 * Aufgabe:
 * - Definiert ein stabiles, parser-sicheres Log-Format
 * - Trennt Log-Daten von deren Darstellung
 *
 * ❗ WICHTIG:
 * - Formatter erzeugen STRINGS
 * - KEINE Persistenz
 * - KEINE Log-Level-Filterung
 *
 * Formatter sitzen zwischen
 * LogEntry und Target.
 *
 * @package   CHK\Logging
 * @author    Michael Korte
 * @license   MIT
 */

namespace CHK\Logging;

interface FormatterInterface
{
    /**
     * Liefert die Kennung des Formats
     * (z. B. "line", "json").
     *
     * @return string
     */
    public function getFormatId(): string;

    /**
     * Liefert die Versionsnummer des Formats.
     *
     * Ermöglicht parser-sichere Weiterentwicklung.
     *
     * @return int
     */
    public function getFormatVersion(): int;

    /**
     * Formatiert einen Log-Eintrag.
     *
     * @param LogEntry $entry
     *
     * @return string
     */
    public function format(LogEntry $entry): string;
}