<?php
declare(strict_types=1);

namespace CHK\Core;

/**
 * Repository relation contract.
 *
 * This interface is intentionally minimal.
 * It does NOT enforce an ORM or query builder.
 * It only declares an explicit opt-in for relation loading.
 *
 * Implementations may:
 * - ignore unsupported relations
 * - validate relations lazily
 * - or throw domain-specific exceptions
 *
 * No automatic joins, no magic.
 */
interface RepositoryInterface
{
    /**
     * Declare relations to be loaded alongside the main entity.
     *
     * Example:
     *
     * $repo->withRelations([
     *     'user' => [
     *         'table'      => 'users',
     *         'localKey'   => 'user_id',
     *         'foreignKey'=> 'id',
     *         'fields'     => ['email', 'firstname'],
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
     * @return static
     */
    public function withRelations(array $relations): static;
}