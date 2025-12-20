<?php
/**
 * Plugin setup
 *
 * Explizite Plugin-Registrierung.
 * Keine Auto-Discovery, keine Magie.
 *
 * Plugins dürfen:
 * - Hooks registrieren
 * - optionale Services bereitstellen
 *
 * Plugins dürfen NICHT:
 * - Routen registrieren
 * - Controller liefern
 * - HTML rendern
 */

/** @var \CHK\Core\App $app */

use Plugins\CoreTrace\CoreTracePlugin;

// Core Trace Plugin (Debug / Observability)
$app->addPlugin(new CoreTracePlugin());