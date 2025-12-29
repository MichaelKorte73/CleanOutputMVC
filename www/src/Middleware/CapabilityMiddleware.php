<?php
declare(strict_types=1);

/**
 * Clean Output MVC
 *
 * Capability Middleware
 *
 * Systemnahe Guard-Middleware zur Validierung von Capabilities.
 *
 * Aufgabe:
 * - Prüft, ob für eine Route deklarierte Capabilities
 *   im System REGISTRIERT sind.
 *
 * ❗ WICHTIG:
 * - KEINE User-/Role-Logik
 * - KEINE Authentifizierung
 * - KEINE Admin-Rechte
 * - KEINE Entscheidung, *wer* etwas darf
 *
 * Diese Middleware stellt ausschließlich sicher,
 * dass benötigte System-Fähigkeiten von Components
 * bereitgestellt werden.
 *
 * Enforcement-Ebene:
 * - "Existiert diese Capability im System?"
 * - NICHT: "Darf der User das?"
 *
 * Typischer Einsatz:
 * - Admin- oder Backend-Routen
 * - Feature-Gates
 * - Technische Abhängigkeiten zwischen Components
 *
 * @package   CHK\Middleware
 * @author    Michael Korte
 * @license   MIT
 */

namespace CHK\Middleware;

use CHK\Core\App;
use CHK\Core\MiddlewareInterface;
use RuntimeException;

final class CapabilityMiddleware implements MiddlewareInterface
{
    /**
     * Prüft deklarierte Route-Capabilities gegen die
     * registrierten Capabilities im Core.
     *
     * Erwartet im Routing-Target optional:
     *
     *  [
     *      'capabilities' => [
     *          'media.read',
     *          'media.write'
     *      ]
     *  ]
     *
     * @param array    $context  Request-Kontext der Middleware-Pipeline
     *                           Erwartete Keys:
     *                           - app     (App)
     *                           - target  (array|null)
     *
     * @param callable $next     Nächste Middleware / Controller
     *
     * @return mixed
     *
     * @throws RuntimeException  Wenn eine benötigte Capability
     *                           nicht von einer Component bereitgestellt wird
     */
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