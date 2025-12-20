<?php
declare(strict_types=1);

namespace CHK\Core;

/**
 * Components liefern fachliche Capabilities.
 * Sie dürfen Routen registrieren, Services anbieten und Hooks abonnieren.
 *
 * Core-Policy: Keine Auto-Discovery. Registrierung erfolgt explizit über App::addComponent().
 */
interface ComponentInterface
{
    public function register(App $app): void;
}