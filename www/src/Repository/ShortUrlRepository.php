<?php
declare(strict_types=1);

/**
 * Clean Output MVC
 *
 * Short URL Repository (Deprecated / Example)
 *
 * Beispielhafte Repository-Implementierung
 * zur Demonstration von Datenzugriff via PDO.
 *
 * ❗ DEPRECATED:
 * - Diese Klasse ist **kein Core-Contract**
 * - Dient ausschließlich als Beispiel
 * - Nicht Teil der finalen Architektur
 *
 * Nicht verwenden für:
 * - produktiven Datenzugriff
 * - Architektur-Referenzen
 * - Core-nahe Implementierungen
 *
 * Wird perspektivisch entfernt.
 *
 * @deprecated
 *
 * @package   CHK\Repository
 * @author    Michael Korte
 * @license   MIT
 */

namespace CHK\Repository;

use PDO;

final class ShortUrlRepository
{
    public function __construct(private PDO $pdo) {}

    /**
     * Prüft, ob ein Slug bereits existiert.
     *
     * @deprecated
     */
    public function slugExists(string $slug): bool
    {
        $stmt = $this->pdo->prepare(
            'SELECT 1 FROM short_urls WHERE slug = :slug LIMIT 1'
        );
        $stmt->execute(['slug' => $slug]);

        return (bool) $stmt->fetchColumn();
    }

    /**
     * Erstellt einen neuen Short-URL-Eintrag.
     *
     * @deprecated
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
     * Findet einen Eintrag anhand des Slugs.
     *
     * @deprecated
     */
    public function findBySlug(string $slug): ?array
    {
        $stmt = $this->pdo->prepare(
            'SELECT * FROM short_urls WHERE slug = :slug'
        );
        $stmt->execute(['slug' => $slug]);

        $row = $stmt->fetch();

        return $row ?: null;
    }

    /**
     * Erhöht den Hit-Zähler.
     *
     * @deprecated
     */
    public function incrementHits(string $slug): void
    {
        $stmt = $this->pdo->prepare(
            'UPDATE short_urls SET hits = hits + 1 WHERE slug = :slug'
        );
        $stmt->execute(['slug' => $slug]);
    }
}