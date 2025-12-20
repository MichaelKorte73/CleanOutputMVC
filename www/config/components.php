<?php
/**
 * Component setup
 *
 * Dieser File registriert alle Components explizit an der App.
 * Keine Auto-Discovery, keine Magie.
 *
 * Wird einmal im Bootstrap geladen.
 */

/** @var \CHK\Core\App $app */

use Components\Shorten\ShortenComponent;

// Components explizit registrieren
$app->addComponent(new ShortenComponent());