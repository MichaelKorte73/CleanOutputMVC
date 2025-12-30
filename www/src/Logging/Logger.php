<?php
declare(strict_types=1);

/**
 * Clean Output MVC
 *
 * Logger
 *
 * Zentrale Logger-Implementierung des Frameworks.
 *
 * Aufgabe:
 * - Entgegennahme von Log-Einträgen
 * - Filterung nach Log-Level (Mask)
 * - Delegation an Formatter und Target
 *
 * ❗ WICHTIG:
 * - KEINE Persistenz-Logik
 * - KEINE Ausgabe-Logik
 * - KEINE Entscheidung über Speicherort
 *
 * Logger ist reiner Orchestrator:
 * Formatter formatiert,
 * Target schreibt.
 *
 * @package   CHK\Logging
 * @author    Michael Korte
 * @license   MIT
 */

namespace CHK\Logging;

final class Logger implements LoggerInterface
{
    /**
     * Bitmask zur Aktivierung von Log-Leveln.
     *
     * @var int
     */
    private int $mask;

    private FormatterInterface $formatter;
    private TargetInterface $target;

    public function __construct(
        int $mask,
        FormatterInterface $formatter,
        TargetInterface $target
    ) {
        $this->mask      = $mask;
        $this->formatter = $formatter;
        $this->target    = $target;
    }

    /**
     * Prüft, ob ein Log-Level aktiv ist.
     *
     * @param int $level
     *
     * @return bool
     */
    public function isEnabled(int $level): bool
    {
        return (bool) ($this->mask & $level);
    }

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
    ): void {
        if (!$this->isEnabled($level)) {
            return;
        }

        $entry = new LogEntry(
            level:     $level,
            levelName: LogLevel::toName($level),
            scope:     $scope,
            origin:    $origin,
            message:   $message,
            context:   $context
        );

        $line = $this->formatter->format($entry);
        $this->target->write($line);
    }
}