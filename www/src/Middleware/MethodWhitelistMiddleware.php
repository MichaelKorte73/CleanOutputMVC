<?php
namespace CHK\Middleware;

use CHK\Core\MiddlewareInterface;
use CHK\Core\Response;

final class MethodWhitelistMiddleware implements MiddlewareInterface
{
    /** @var string[] */
    private array $allowed;

    /**
     * @param string[] $allowedMethods
     */
    public function __construct(array $allowedMethods = ['GET', 'POST'])
    {
        $this->allowed = array_map('strtoupper', $allowedMethods);
    }

    public function handle(array $context, callable $next): mixed
    {
        $request = $context['request'] ?? null;

        if (!$request) {
            // kein Request → nichts zu prüfen
            return $next($context);
        }

        $method = strtoupper($request->getMethod());

        if (!in_array($method, $this->allowed, true)) {
            return Response::methodNotAllowed($this->allowed);
        }

        return $next($context);
    }
}