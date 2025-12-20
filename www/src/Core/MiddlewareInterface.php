<?php
namespace CHK\Core;

interface MiddlewareInterface
{
    /**
     * @param array $context  z.B. route, params, request, app
     * @param callable $next
     */
    public function handle(array $context, callable $next): mixed;
}