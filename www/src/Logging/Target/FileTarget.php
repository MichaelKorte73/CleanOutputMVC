<?php
namespace CHK\Logging\Target;

use CHK\Logging\TargetInterface;

final class FileTarget implements TargetInterface
{
    private string $file;

    public function __construct(string $file)
    {
        $this->file = $file;
    }

    public function write(string $line): void
    {
        file_put_contents(
            $this->file,
            $line,
            FILE_APPEND | LOCK_EX
        );
    }
}