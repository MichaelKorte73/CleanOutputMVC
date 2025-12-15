<?php
/**
 * MiddlewareInterface
 *
 * Author: Michael Korte
 * Mail: mkorte@korte-software.de
 * Company: Michael Korte Software
 * Version: 0.1
 * Date: 13.12.2025
 *
 * Contract for all middleware implementations.
 *
 * A middleware acts as a guard or interceptor in the request lifecycle.
 * It may:
 * - inspect the request or context
 * - block execution and return a response
 * - modify the context (carefully)
 * - or delegate execution to the next middleware
 *
 * Middleware must never:
 * - render views
 * - access PageContext
 * - perform domain logic
 */

namespace CHK\Core;

interface MiddlewareInterface
{
    /**
     * Handle the middleware execution.
     *
     * @param array    $context Shared execution context
     *                           (e.g. request, route, params, app)
     * @param callable $next    Next middleware or final destination
     *
     * @return mixed Response or result of the next callable
     */
    public function handle(array $context, callable $next): mixed;
}