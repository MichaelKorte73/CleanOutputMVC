<?php
declare(strict_types=1);

namespace CHK\Core;

/**
 * Clean Output MVC
 *
 * Repository Relation Contract
 *
 * Minimaler, expliziter Vertrag zur Deklaration von Relationen.
 *
 * ❗ Dieses Interface:
 * - erzwingt KEIN ORM
 * - erzwingt KEIN Query-Builder
 * - implementiert KEINE Lade-Logik
 *
 * Es dient ausschließlich als Opt-in-Signal:
 * „Dieses Repository ist in der Lage, Relationen bewusst zu laden.“
 *
 * Implementierungen dürfen:
 * - unbekannte Relationen ignorieren
 * - Relationen lazy validieren
 * - domain-spezifische Exceptions werfen
 *
 * ❌ Keine automatischen Joins
 * ❌ Keine implizite Magie
 *
 * @package   CHK\Core
 * @author    Michael Korte
 * @license   MIT
 */
interface RepositoryInterface
{
    /**
     * Deklariert Relationen, die gemeinsam mit der Haupt-Entität
     * geladen werden sollen.
     *
     * Beispiel:
     *
     * $repo->withRelations([
     *     'user' => [
     *         'table'       => 'users',
     *         'localKey'    => 'user_id',
     *         'foreignKey' => 'id',
     *         'fields'      => ['email', 'firstname'],
     *     ],
     * ]);
     *
     * @param array<string, array{
     *     table: string,
     *     localKey: string,
     *     foreignKey: string,
     *     fields?: string[]
     * }> $relations
     *
     * @return static Ermöglicht fluentes Chaining
     */
    public function withRelations(array $relations): static;
}