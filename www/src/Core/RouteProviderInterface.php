<?php
namespace CHK\Core;

use CHK\Core\Router;

interface RouteProviderInterface
{
    public function registerRoutes(Router $router): void;
}