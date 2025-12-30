<?php
declare(strict_types=1);

/**
 * Clean Output MVC
 *
 * Log Level
 *
 * Definition der verfügbaren Log-Level
 * als Bitmasken.
 *
 * Aufgabe:
 * - Einheitliche Log-Level im Framework
 * - Kombinierbar über Bitmasken
 *
 * ❗ WICHTIG:
 * - KEINE Logik
 * - KEINE Ausgabe
 * - KEINE Persistenz
 *
 * Wird vom Logger zur Filterung
 * und vom Formatter zur Darstellung verwendet.
 *
 * @package   CHK\Logging
 * @author    Michael Korte
 * @license   MIT
 */

namespace CHK\Logging;

final class LogLevel
{
    public const FATAL    = 1 << 0;
    public const CRITICAL = 1 << 1;
    public const ERROR    = 1 << 2;
    public const WARNING  = 1 << 3;
    public const INFO     = 1 << 4;
    public const DEBUG    = 1 << 5;
    public const TRACE    = 1 << 6;

    /**
     * Maske für alle Log-Level.
     */
    public const ALL =
        self::FATAL
      | self::CRITICAL
      | self::ERROR
      | self::WARNING
      | self::INFO
      | self::DEBUG
      | self::TRACE;

    /**
     * Wandelt einen Log-Level in seinen Namen.
     *
     * @param int $level
     *
     * @return string
     */
    public static function toName(int $level): string
    {
        return match ($level) {
            self::FATAL    => 'FATAL',
            self::CRITICAL => 'CRITICAL',
            self::ERROR    => 'ERROR',
            self::WARNING  => 'WARNING',
            self::INFO     => 'INFO',
            self::DEBUG    => 'DEBUG',
            self::TRACE    => 'TRACE',
            default        => 'UNKNOWN',
        };
    }
}