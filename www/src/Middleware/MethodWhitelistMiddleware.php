<?php
/**
 * Clean Output MVC
 *
 * HTTP Method Whitelist Middleware
 *
 * Ensures that only explicitly allowed HTTP methods are processed.
 * All other request methods are rejected early in the middleware layer.
 *
 * This middleware acts as a guard-layer and prevents unsupported or
 * unintended HTTP methods from reaching controllers or domain logic.
 *
 * Typical use cases:
 * - Enforce GET/POST only
 * - Protect endpoints from PUT, DELETE, PATCH, etc.
 *
 * @author    Michael Korte
 * @email     mkorte@korte-software.de
 * @company   Michael Korte Software
 *
 * @version   0.1
 * @date      13.12.2025
 *
 * @package   CHK\Middleware
 */

namespace CHK\Middleware;

use CHK\Core\MiddlewareInterface;
use CHK\Core\Response;

final class MethodWhitelistMiddleware implements MiddlewareInterface
{
    /**
     * List of allowed HTTP methods.
     *
     * Stored uppercase for strict comparison.
     *
     * @var string[]
     */
    private array $allowed;

    /**
     * @param string[] $allowedMethods Allowed HTTP methods (default: GET, POST)
     */
    public function __construct(array $allowedMethods = ['GET', 'POST'])
    {
        $this->allowed = array_map('strtoupper', $allowedMethods);
    }

    /**
     * Handle middleware execution.
     *
     * @param array    $context Middleware context (must contain request)
     * @param callable $next    Next middleware callback
     *
     * @return mixed
     */
    public function handle(array $context, callable $next): mixed
    {
        $request = $context['request'] ?? null;

        // If no request is present, skip method validation
        if (!$request) {
            return $next($context);
        }

        $method = strtoupper($request->getMethod());

        // Reject unsupported HTTP methods
        if (!in_array($method, $this->allowed, true)) {
            return Response::methodNotAllowed($this->allowed);
        }

        return $next($context);
    }
}