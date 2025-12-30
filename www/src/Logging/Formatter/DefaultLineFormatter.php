<?php
declare(strict_types=1);

/**
 * Clean Output MVC
 *
 * Default Line Formatter
 *
 * Standardmäßiger, zeilenbasierter Log-Formatter.
 *
 * Eigenschaften:
 * - stabil
 * - parser-sicher
 * - menschenlesbar
 *
 * Format-ID:
 * - line
 *
 * Version:
 * - v1
 *
 * Ausgabeformat:
 * [timestamp] [LEVEL] [scope] [origin] message {context}
 *
 * @package   CHK\Logging\Formatter
 * @author    Michael Korte
 * @license   MIT
 */

namespace CHK\Logging\Formatter;

use CHK\Logging\FormatterInterface;
use CHK\Logging\LogEntry;

final class DefaultLineFormatter implements FormatterInterface
{
    /**
     * {@inheritdoc}
     */
    public function getFormatId(): string
    {
        return 'line';
    }

    /**
     * {@inheritdoc}
     */
    public function getFormatVersion(): int
    {
        return 1;
    }

    /**
     * {@inheritdoc}
     */
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
        ) . "\n";
    }
}