<?php

/**
 * Clean Output MVC
 *
 * Plugin Setup
 *
 * Zentrale Registrierungsdatei für Plugins.
 *
 * Grundprinzip:
 * - Explizite Registrierung
 * - KEINE Auto-Discovery
 * - KEINE Magie
 *
 * Plugins dürfen:
 * - Hooks registrieren
 * - optionale Services bereitstellen
 * - Observability / Erweiterungen liefern
 *
 * Plugins dürfen NICHT:
 * - Routen registrieren
 * - Controller liefern
 * - HTML rendern
 *
 * Plugins greifen ausschließlich
 * über das Hook-System ein.
 */

/** @var \CHK\Core\App $app */

use Plugins\CoreTrace\CoreTracePlugin;

/**
 * Core Trace Plugin
 *
 * Zweck:
 * - Debugging
 * - Observability
 * - Laufzeit-Analyse
 *
 * Aktivierung erfolgt bewusst explizit.
 */
$app->addPlugin(new CoreTracePlugin());