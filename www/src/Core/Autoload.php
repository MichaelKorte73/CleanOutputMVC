<?php
declare(strict_types=1);

/**
 * Clean Output MVC
 *
 * Core Autoloader
 *
 * Minimalistischer, expliziter PSR-4-ähnlicher Autoloader
 * für Projekt- und Framework-Code.
 *
 * Design-Ziele:
 * - Keine Vendor-Logik
 * - Keine Auto-Discovery
 * - Kein Fallback-Raten
 * - Keine Namespace-Magie
 *
 * Eigenschaften:
 * - Lädt ausschließlich explizit registrierte Namespace-Präfixe
 * - Composer-kompatibel (kann später ersetzt oder ergänzt werden)
 * - Deterministisches Verhalten
 *
 * ❗ KEIN Ersatz für Composer im Vendor-Kontext
 * ❗ NUR für Core / App / Components / Plugins
 *
 * @package   CHK\Core
 * @author    Michael Korte
 * @license   MIT
 */

namespace CHK\Core;

final class Autoload
{
    /**
     * Registrierte Namespace-Präfixe.
     *
     * key   = Namespace-Prefix (inkl. abschließendem Backslash)
     * value = Basisverzeichnis
     *
     * @var array<string,string>
     */
    private static array $prefixes = [];

    /**
     * Registriert den Autoloader und die erlaubten Namespace-Präfixe.
     *
     * Beispiel:
     * Autoload::register([
     *   'CHK\\'        => __DIR__ . '/../src',
     *   'App\\'        => __DIR__ . '/../custom/app',
     *   'Components\\' => __DIR__ . '/../custom/components',
     *   'Plugins\\'    => __DIR__ . '/../custom/plugins',
     * ]);
     *
     * ❗ Präfixe werden NICHT gemerged oder geraten
     * ❗ Jeder Prefix ist explizit
     *
     * @param array<string,string> $prefixes
     */
    public static function register(array $prefixes): void
    {
        foreach ($prefixes as $prefix => $baseDir) {
            self::$prefixes[rtrim($prefix, '\\') . '\\']
                = rtrim($baseDir, '/');
        }

        spl_autoload_register([self::class, 'load']);
    }

    /**
     * Lädt eine Klasse anhand der registrierten Namespace-Präfixe.
     *
     * Ablauf:
     * 1. Prefix-Match prüfen
     * 2. Relativen Klassennamen berechnen
     * 3. Dateipfad ableiten
     * 4. Datei laden, falls vorhanden
     *
     * ❗ Kein Error, wenn Datei fehlt
     * ❗ Kein Fallback auf andere Prefixe
     *
     * @param string $class Vollqualifizierter Klassenname
     */
    private static function load(string $class): void
    {
        foreach (self::$prefixes as $prefix => $baseDir) {
            if (str_starts_with($class, $prefix)) {
                $relativeClass = substr($class, strlen($prefix));

                $file = $baseDir . '/'
                    . str_replace('\\', '/', $relativeClass)
                    . '.php';

                if (is_file($file)) {
                    require $file;
                }

                // ❗ Wichtig: kein weiteres Durchprobieren