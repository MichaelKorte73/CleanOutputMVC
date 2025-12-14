<?php
namespace CHK\Repository;

use PDO;

final class ShortUrlRepository
{
    public function __construct(private PDO $pdo) {}

    public function slugExists(string $slug): bool
    {
        $stmt = $this->pdo->prepare(
            'SELECT 1 FROM short_urls WHERE slug = :slug LIMIT 1'
        );
        $stmt->execute(['slug' => $slug]);

        return (bool) $stmt->fetchColumn();
    }

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

    public function findBySlug(string $slug): ?array
    {
        $stmt = $this->pdo->prepare(
            'SELECT * FROM short_urls WHERE slug = :slug'
        );
        $stmt->execute(['slug' => $slug]);

        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function incrementHits(string $slug): void
    {
        $stmt = $this->pdo->prepare(
            'UPDATE short_urls SET hits = hits + 1 WHERE slug = :slug'
        );
        $stmt->execute(['slug' => $slug]);
    }
}