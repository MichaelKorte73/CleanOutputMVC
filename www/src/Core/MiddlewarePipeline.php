<?php
namespace CHK\Core;

final class MiddlewarePipeline
{
    /** @var MiddlewareInterface[] */
    private array $stack = [];

    public function add(MiddlewareInterface $middleware): void
    {
        $this->stack[] = $middleware;
    }

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