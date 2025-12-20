<?php
namespace App;

use CHK\Core\RouteProviderInterface;
use CHK\Core\Router;

final class AppRoutes implements RouteProviderInterface
{
    public function registerRoutes(Router $router): void
    {
        $router->map('GET', '/', [
            'controller' => \App\Controller\HomeController::class,
            'action'     => 'index',
        ]);
    }
}