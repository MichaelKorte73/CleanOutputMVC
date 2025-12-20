<?php
namespace CHK\Logging\Formatter;

use CHK\Logging\LogEntry;
use CHK\Logging\FormatterInterface;

/**
 * Default line-based log formatter (line-v1).
 *
 * Stable, parser-safe, human-readable.
 */
final class DefaultLineFormatter implements FormatterInterface
{
    public function getFormatId(): string
    {
        return 'line';
    }

    public function getFormatVersion(): int
    {
        return 1;
    }

    public function format(LogEntry $entry): string
    {
        $timestamp = sprintf(
            '%.3f',
            $entry->getTimestamp()
        );

        $context = $entry->getContext();
        $contextString = '';

        if (!empty($context)) {
            $contextString = ' ' . json_encode(
                $context,
                JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
            );
        }

        return sprintf(
            '[%s] [%s] [%s] [%s] %s%s',
            $timestamp,
            $entry->getLevelName(),
            $entry->getScope(),
            $entry->getOrigin(),
            $entry->getMessage(),
            $contextString
        )."\n";
    }
}