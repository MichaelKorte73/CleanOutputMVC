<?php
declare(strict_types=1);

namespace CHK\Core;

/**
 * Simple, explicit PSR-4–like autoloader for project code.
 *
 * - No vendor loading
 * - No guessing
 * - No namespace rewriting
 * - Composer-compatible replacement later
 */
final class Autoload
{
    /**
     * @var array<string,string>
     * Namespace prefix => base directory
     */
    private static array $prefixes = [];

    /**
     * Register the autoloader.
     *
     * Example:
     * Autoload::register([
     *   'CHK\\'        => __DIR__ . '/../src',
     *   'App\\'        => __DIR__ . '/../app',
     *   'Components\\' => __DIR__ . '/../components',
     *   'Plugins\\'    => __DIR__ . '/../plugins',
     * ]);
     */
    public static function register(array $prefixes): void
    {
        foreach ($prefixes as $prefix => $baseDir) {
            self::$prefixes[rtrim($prefix, '\\') . '\\']
                = rtrim($baseDir, '/');
        }

        spl_autoload_register([self::class, 'load']);
    }

    private static function load(string $class): void
    {
        foreach (self::$prefixes as $prefix => $baseDir) {
            if (str_starts_with($class, $prefix)) {
                $relativeClass = substr($class, strlen($prefix));
                $file = $baseDir
                    . '/'
                    . str_replace('\\', '/', $relativeClass)
                    . '.php';

                if (is_file($file)) {

                    require $file;
                }

                return;
            }
        }
    }
}