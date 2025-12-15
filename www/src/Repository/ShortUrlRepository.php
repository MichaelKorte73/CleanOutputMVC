<?php

namespace CHK\Repository;

use PDO;

/**
 * ShortUrlRepository
 *
 * Repository for accessing and mutating short URL records.
 * Encapsulates all database interactions related to short URLs.
 *
 * Responsibilities:
 * - check slug existence
 * - create new short URLs
 * - resolve short URLs
 * - update hit counters
 *
 * This repository contains no business logic.
 * Validation and collision handling are handled by the controller or service layer.
 *
 * @author  Michael Korte
 * @email   mkorte@korte-software.de
 * @company Michael Korte Software
 * @version 0.1
 * @date    13.12.2025
 */
final class ShortUrlRepository
{
    /**
     * @param PDO $pdo Active database connection
     */
    public function __construct(
        private PDO $pdo
    ) {
    }

    /**
     * Check whether a slug already exists.
     *
     * @param string $slug
     * @return bool True if slug exists
     */
    public function slugExists(string $slug): bool
    {
        $stmt = $this->pdo->prepare(
            'SELECT 1 FROM short_urls WHERE slug = :slug LIMIT 1'
        );

        $stmt->execute([
            'slug' => $slug,
        ]);

        return (bool) $stmt->fetchColumn();
    }

    /**
     * Create a new short URL entry.
     *
     * @param string $slug
     * @param string $targetUrl
     * @return void
     */
    public function create(string $slug, string $targetUrl): void
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO short_urls (slug, target_url, created_at)
             VALUES (:slug, :url, NOW())'
        );

        $stmt->execute([
            'slug' => $slug,
            'url'  => $targetUrl,
        ]);
    }

    /**
     * Find a short URL by its slug.
     *
     * @param string $slug
     * @return array|null Database row or null if not found
     */
    public function findBySlug(string $slug): ?array
    {
        $stmt = $this->pdo->prepare(
            'SELECT * FROM short_urls WHERE slug = :slug'
        );

        $stmt->execute([
            'slug' => $slug,
        ]);

        $row = $stmt->fetch();

        return $row ?: null;
    }

    /**
     * Increment the hit counter for a short URL.
     *
     * @param string $slug
     * @return void
     */
    public function incrementHits(string $slug): void
    {
        $stmt = $this->pdo->prepare(
            'UPDATE short_urls
             SET hits = hits + 1
             WHERE slug = :slug'
        );

        $stmt->execute([
            'slug' => $slug,
        ]);
    }
}