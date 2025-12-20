<?php
namespace CHK\Logging;

interface TargetInterface
{
    /**
     * Writes a formatted log line.
     */
    public function write(string $line): void;
}