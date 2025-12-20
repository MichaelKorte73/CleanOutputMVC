<?php
declare(strict_types=1);

namespace Components\Shorten;

use CHK\Core\RouteProviderInterface;
use CHK\Core\Router;

final class Routes implements RouteProviderInterface
{
    public function registerRoutes(Router $router): void
    {
        $router->map('POST', '/shorten', [
            'controller' => Controller\ShortenController::class,
            'action'     => 'create',
        ]);

        $router->map('GET', '/[a:slug]', [
            'controller' => Controller\ShortenController::class,
            'action'     => 'resolve',
        ]);
    }
}