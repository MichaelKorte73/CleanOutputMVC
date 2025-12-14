<?php
namespace CHK\Middleware;

use CHK\Core\MiddlewareInterface;
use CHK\Core\Response;

final class RateLimitMiddleware implements MiddlewareInterface
{
    public function handle(array $context, callable $next): mixed
    {
        $request = $context['request'] ?? null;

        if (!$request) {
            return $next($context);
        }

        $ip = $request->getIp() ?? 'unknown';

        // Beispiel: sehr simple Rate-Limit-Logik
        if ($this->isRateLimited($ip)) {
            return Response::tooManyRequests();
        }

        return $next($context);
    }

    private function isRateLimited(string $ip): bool
    {
        // TODO: später Storage (APCu, Redis, File, DB)
        return false;
    }
}