<?php
declare(strict_types=1);

/**
 * Clean Output MVC
 *
 * Database Wrapper
 *
 * Dünner, expliziter Wrapper um PDO.
 *
 * Aufgaben:
 * - Initialisierung der PDO-Verbindung
 * - Einheitliche PDO-Konfiguration
 * - Bereitstellung der PDO-Instanz für Repositories / Services
 *
 * ❗ KEIN ORM
 * ❗ KEINE Query-Abstraktion
 * ❗ KEINE Business-Logik
 *
 * Der Core stellt lediglich die Verbindung bereit.
 * Abfragen gehören in Repositories oder Services.
 *
 * Failure-Policy:
 * - PDOException wird NICHT abgefangen
 * - Bootstrap entscheidet über Abbruch / Fallback
 *
 * @package   CHK\Core
 * @author    Michael Korte
 * @license   MIT
 */

namespace CHK\Core;

use PDO;

final class Database
{
    /**
     * Aktive PDO-Verbindung.
     */
    private PDO $pdo;

    /**
     * Erstellt eine neue PDO-Verbindung.
     *
     * Erwartetes Konfigurationsschema:
     * [
     *   'dsn'      => 'mysql:host=localhost;dbname=app',
     *   'user'     => 'dbuser',
     *   'password' => 'secret',
     * ]
     *
     * ❗ Fehler werden absichtlich nicht abgefangen
     * ❗ PDOException bricht Bootstrap hart ab
     *
     * @param array $cfg Datenbank-Konfiguration
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
     * Liefert die rohe PDO-Instanz.
     *
     * Verwendung:
     * - Repositories
     * - Low-Level-Queries
     * - Migrations / Tools
     *
     * @return PDO
     */
    public function pdo(): PDO
    {
        return $this->pdo;
    }
}