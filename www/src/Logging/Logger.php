<?php
namespace CHK\Logging;

final class Logger implements LoggerInterface
{
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

    public function isEnabled(int $level): bool
    {
        return (bool) ($this->mask & $level);
    }

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