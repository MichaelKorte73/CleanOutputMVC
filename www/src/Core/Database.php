<?php

namespace CHK\Core;

use PDO;

/**
 * Database
 *
 * Thin PDO wrapper used by the application.
 *
 * Responsibilities:
 * - Create and configure the PDO instance
 * - Expose PDO for repositories and services
 *
 * Notes:
 * - No query helpers
 * - No abstraction layer
 * - No magic
 *
 * The Database class is intentionally minimal.
 * Domain logic belongs in repositories, not here.
 */
final class Database
{
    /**
     * Underlying PDO instance.
     */
    private PDO $pdo;

    /**
     * Create a new database connection.
     *
     * Expected config keys:
     * - dsn
     * - user
     * - password
     *
     * @param array{
     *     dsn:string,
     *     user:string,
     *     password:string
     * } $cfg
     */
    public function __construct(array $cfg)
    {
        $this->pdo = new PDO(
            $cfg['dsn'],
            $cfg['user'],
            $cfg['password'],
            [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]
        );
    }

    /**
     * Get the raw PDO instance.
     *
     * @return PDO
     */
    public function pdo(): PDO
    {
        return $this->pdo;
    }
}