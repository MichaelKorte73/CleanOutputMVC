<?php
namespace CHK\Logging\Target;

use CHK\Logging\TargetInterface;

final class StdErrTarget implements TargetInterface
{
    public function write(string $line): void
    {
        file_put_contents('php://stderr', $line);
    }
}