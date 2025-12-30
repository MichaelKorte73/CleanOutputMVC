<?php

/**
 * Clean Output MVC
 *
 * Credentials / Secrets
 *
 * ⚠️ WICHTIG:
 * - Diese Datei darf **NIEMALS committed** werden
 * - Muss in `.gitignore` stehen
 *
 * Enthält:
 * - Zugangsdaten
 * - Secrets
 * - Umgebungsabhängige Konfiguration
 *
 * Wird bewusst **separat** von der
 * Hauptkonfiguration gehalten.
 */

return [

    /**
     * Datenbank-Zugangsdaten.
     *
     * Wird vom Bootstrap verwendet,
     * um den DB-Service zu initialisieren.
     */
    'db' => [
        'dsn'      => 'mysql:host=HOSTNAME;dbname=DBNAME;charset=utf8mb4',
        'user'     => 'USER',
        'password' => 'PWF',
    ],

    /**
     * Platzhalter für weitere Secrets:
     *
     * 'redis' => [ ... ]
     * 'mail'  => [ ... ]
     */
];