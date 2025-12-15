<?php
/**
 * MiddlewarePipeline
 *
 * Author: Michael Korte
 * Mail: mkorte@korte-software.de
 * Company: Michael Korte Software
 * Version: 0.1
 * Date: 13.12.2025
 *
 * Executes middleware in a stacked pipeline.
 *
 * Responsibilities:
 * - Store middleware in execution order
 * - Build a callable pipeline at runtime
 * - Pass a shared context through all middleware
 *
 * Notes:
 * - Middleware is executed in the order it was added
 * - Each middleware must explicitly call $next()
 * - No rendering, no request mutation
 */

namespace CHK\Core;

final class MiddlewarePipeline
{
    /**
     * Registered middleware stack.
     *
     * @var MiddlewareInterface[]
     */
    private array $stack = [];

    /**
     * Add middleware to the pipeline.
     *
     * @param MiddlewareInterface $middleware
     * @return void
     */
    public function add(MiddlewareInterface $middleware): void
    {
        $this->stack[] = $middleware;
    }

    /**
     * Execute the middleware pipeline.
     *
     * @param array    $context      Shared execution context
     * @param callable $destination  Final callable after middleware
     * @return mixed
     */
    public function handle(array $context, callable $destination): mixed
    {
        $pipeline = array_reduce(
            array_reverse($this->stack),
            fn ($next, MiddlewareInterface $middleware) =>
                fn ($ctx) => $middleware->handle($ctx, $next),
            $destination
        );

        return $pipeline($context);
    }
}