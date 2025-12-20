<?php
namespace CHK\Logging;

interface LoggerInterface
{
    public function log(
        int $level,
        string $scope,
        string $origin,
        string $message,
        array $context = []
    ): void;

    public function isEnabled(int $level): bool;
}