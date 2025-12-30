<?php
declare(strict_types=1);

/**
 * Clean Output MVC
 *
 * Log Entry
 *
 * Immutable Value-Object zur Repräsentation
 * eines einzelnen Log-Ereignisses.
 *
 * Aufgabe:
 * - Kapselt alle Informationen eines Log-Eintrags
 * - Wird vor der Formatierung erzeugt
 *
 * ❗ WICHTIG:
 * - IMMUTABLE (keine Setter)
 * - KEINE Formatierungslogik
 * - KEINE Persistenz
 *
 * LogEntry dient ausschließlich als
 * Datencontainer zwischen Logger,
 * Formatter und Target.
 *
 * @package   CHK\Logging
 * @author    Michael Korte
 * @license   MIT
 */

namespace CHK\Logging;

final class LogEntry
{
    private float $timestamp;
    private int $level;
    private string $levelName;
    private string $scope;
    private string $origin;
    private string $message;
    private array $context;

    public function __construct(
        int $level,
        string $levelName,
        string $scope,
        string $origin,
        string $message,
        array $context = []
    ) {
        $this->timestamp = microtime(true);
        $this->level     = $level;
        $this->levelName = $levelName;
        $this->scope     = $scope;
        $this->origin    = $origin;
        $this->message   = $message;
        $this->context   = $context;
    }

    /**
     * Zeitpunkt der Erstellung (Unix-Timestamp mit Mikrosekunden).
     */
    public function getTimestamp(): float
    {
        return $this->timestamp;
    }

    /**
     * Numerischer Log-Level (Bitmask).
     */
    public function getLevel(): int
    {
        return $this->level;
    }

    /**
     * Name des Log-Levels.
     */
    public function getLevelName(): string
    {
        return $this->levelName;
    }

    /**
     * Log-Scope (z. B. app, core, security).
     */
    public function getScope(): string
    {
        return $this->scope;
    }

    /**
     * Ursprung des Log-Eintrags (Klasse / Kontext).
     */
    public function getOrigin(): string
    {
        return $this->origin;
    }

    /**
     * Log-Nachricht.
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * Zusatzdaten zum Log-Eintrag.
     *
     * @return array
     */
    public function getContext(): array
    {
        return $this->context;
    }
}