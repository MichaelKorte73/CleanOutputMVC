<?php
declare(strict_types=1);

namespace Components\Shorten;

use CHK\Core\App;
use CHK\Core\ComponentInterface;

/**
 * Minimal-Component (v0.2-Referenz):
 * - registriert eigene Routes
 * - keine Auto-Discovery
 * - keine Seiten im Core
 */
final class ShortenComponent implements ComponentInterface
{
    public function register(App $app): void
    {
        (new Routes())->registerRoutes($app->getRouter());
    }
}