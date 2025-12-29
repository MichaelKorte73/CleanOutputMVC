<?php
declare(strict_types=1);

/**
 * Clean Output MVC
 *
 * Capability Middleware
 *
 * Prüft, ob für eine Route benötigte Capabilities
 * im System registriert sind.
 *
 * ❗ KEINE User-/Role-Logik
 * ❗ KEINE Admin-Rechte
 * ❗ Nur System-Fähigkeiten
 *
 * @package   CHK\Core\Middleware
 * @author    Michael Korte
 * @license   MIT
 */

namespace CHK\Middleware;

use CHK\Core\App;
use CHK\Core\MiddlewareInterface;
use RuntimeException;

final class CapabilityMiddleware implements MiddlewareInterface
{
    public function handle(array $context, callable $next): mixed
    {
        /** @var App $app */
        $app = $context['app'];

        $target = $context['target'] ?? null;
        if (!is_array($target)) {
            return $next($context);
        }

        $required = $target['capabilities'] ?? null;
        if (!$required || !is_array($required)) {
            return $next($context);
        }

        foreach ($required as $capability) {
            if (!$app->hasCapability($capability)) {
                throw new RuntimeException(
                    "Required capability '{$capability}' not provided by any component"
                );
            }
        }

        return $next($context);
    }
}