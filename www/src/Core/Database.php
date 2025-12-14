<?php
namespace CHK\Core;

use PDO;

final class Database
{
    private PDO $pdo;

    public function __construct(array $cfg)
    {
        $this->pdo = new PDO(
            $cfg['dsn'],
            $cfg['user'],
            $cfg['password'],
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]
        );
    }

    public function pdo(): PDO
    {
        return $this->pdo;
    }
}