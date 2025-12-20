<?php
namespace CHK\Logging;

/**
 * Immutable log entry.
 *
 * Represents a single logging event before formatting.
 */
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

    public function getTimestamp(): float
    {
        return $this->timestamp;
    }

    public function getLevel(): int
    {
        return $this->level;
    }

    public function getLevelName(): string
    {
        return $this->levelName;
    }

    public function getScope(): string
    {
        return $this->scope;
    }

    public function getOrigin(): string
    {
        return $this->origin;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function getContext(): array
    {
        return $this->context;
    }
}