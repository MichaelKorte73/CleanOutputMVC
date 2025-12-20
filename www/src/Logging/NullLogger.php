<?php
namespace CHK\Logging;

final class NullLogger implements LoggerInterface
{
    public function isEnabled(int $level): bool
    {
        return false;
    }

    public function log(
        int $level,
        string $scope,
        string $origin,
        string $message,
        array $context = []
    ): void {
        // intentionally empty
    }
}