<?php
namespace CHK\Middleware;

use CHK\Core\MiddlewareInterface;
use CHK\Core\Response;

final class AbuseBurstMiddleware implements MiddlewareInterface
{
    private int $maxHits;
    private int $window; // Sekunden

    /** @var array<string,array{count:int,ts:int}> */
    private static array $hits = [];

    public function __construct(int $maxHits = 10, int $window = 2)
    {
        $this->maxHits = $maxHits;
        $this->window  = $window;
    }

    public function handle(array $context, callable $next): mixed
    {
        $request = $context['request'] ?? null;

        if (!$request) {
            return $next($context);
        }

        $ip    = $request->getIp() ?? 'unknown';
        $agent = substr($request->getHeader('User-Agent') ?? 'na', 0, 120);

        $key = sha1($ip . '|' . $agent);
        $now = time();

        $entry = self::$hits[$key] ?? ['count' => 0, 'ts' => $now];

        // Fenster abgelaufen → reset
        if ($now - $entry['ts'] > $this->window) {
            $entry = ['count' => 0, 'ts' => $now];
        }

        $entry['count']++;
        self::$hits[$key] = $entry;

        if ($entry['count'] > $this->maxHits) {
            return Response::tooManyRequests();
        }

        return $next($context);
    }
}