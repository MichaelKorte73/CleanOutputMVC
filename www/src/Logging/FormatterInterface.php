<?php
namespace CHK\Logging;

use CHK\Logging\LogEntry;

/**
 * Defines a stable, parser-safe log format.
 */
interface FormatterInterface
{
    /**
     * Returns the format identifier (e.g. "line", "json").
     */
    public function getFormatId(): string;

    /**
     * Returns the format version.
     */
    public function getFormatVersion(): int;

    /**
     * Formats a log entry into a string.
     */
    public function format(LogEntry $entry): string;
}