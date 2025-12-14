<?php
namespace CHK\Middleware;

use CHK\Core\MiddlewareInterface;
use CHK\Core\Response;

final class PayloadSizeMiddleware implements MiddlewareInterface
{
    private int $maxBytes;

    public function __construct(int $maxBytes = 1048576) // 1 MB
    {
        $this->maxBytes = $maxBytes;
    }

    public function handle(array $context, callable $next): mixed
    {
        $request = $context['request'] ?? null;

        if (!$request) {
            return $next($context);
        }

        $length = (int) ($request->getHeader('Content-Length') ?? 0);

        if ($length > 0 && $length > $this->maxBytes) {
            return Response::payloadTooLarge();
        }

        return $next($context);
    }
}