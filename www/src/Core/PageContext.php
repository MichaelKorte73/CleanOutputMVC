<?php
declare(strict_types=1);

/**
 * Clean Output MVC
 *
 * PageContext
 *
 * Zentrale, mutable Seiten-Datenstruktur.
 *
 * Wird aufgebaut durch:
 * - Controller (primär)
 * - Components (über Hooks)
 *
 * Wird konsumiert durch:
 * - Renderer (read-only)
 *
 * Verantwortlich für:
 * - HTTP-Status & Meta-Daten
 * - View-Daten (klassisch)
 * - Asset-Handles (Styles / Scripts)
 * - Block-Konfiguration (v0.3+)
 *
 * ❗ Enthält KEINE Rendering-Logik
 * ❗ Enthält KEINE Business-Logik
 * ❗ Wird pro Request neu instanziiert
 *
 * @package   CHK\Core
 * @author    Michael Korte
 * @license   MIT
 */

namespace CHK\Core;

final class PageContext
{
    // -------------------------------------------------
    // STATUS / META
    // -------------------------------------------------

    /** HTTP-Statuscode der Response */
    public int $status = 200;

    /** <title>-Inhalt */
    public string $title = '';

    /** Meta-Description */
    public ?string $description = null;

    // -------------------------------------------------
    // VIEW DATA (klassisch, Twig)
    // -------------------------------------------------

    /**
     * Freie View-Daten für Templates.
     * Wird unverändert an den Renderer übergeben.
     */
    private array $viewData = [];

    // -------------------------------------------------
    // ASSETS (Handles, keine URLs)
    // -------------------------------------------------

    /**
     * Style-Handles (z.B. "base", "admin")
     */
    private array $styles  = [];

    /**
     * Script-Handles (z.B. "core", "editor")
     */
    private array $scripts = [];

    // -------------------------------------------------
    // BLOCKS (v0.3+)
    // -------------------------------------------------

    /**
     * Block-Konfigurationen.
     * Struktur wird vom BlockRenderer interpretiert.
     */
    private array $blocks = [];

    // -------------------------------------------------
    // META
    // -------------------------------------------------

    public function withStatus(int $status): self
    {
        $this->status = $status;
        return $this;
    }

    /**
     * Opt-in für globale Basis-Assets.
     *
     * Gedacht für:
     * - Admin
     * - Base-Layouts
     *
     * ❗ Kein Auto-Load
     * ❗ Keine Magie
     */
    public function withGlobals(): self
    {
        $this
            ->addStyle('reset')
            ->addStyle('base')
            ->addStyle('typography')
            ->addStyle('grid')
            ->addStyle('layout')
            ->addStyle('helpers')
            ->addScript('core');

        return $this;
    }

    public function withMeta(string $title, ?string $description = null): self
    {
        $this->title       = $title;
        $this->description = $description;
        return $this;
    }

    // -------------------------------------------------
    // ASSETS
    // -------------------------------------------------

    public function addStyle(string $name): self
    {
        if (!in_array($name, $this->styles, true)) {
            $this->styles[] = $name;
        }
        return $this;
    }

    public function addScript(string $name): self
    {
        if (!in_array($name, $this->scripts, true)) {
            $this->scripts[] = $name;
        }
        return $this;
    }

    public function getStyles(): array
    {
        return $this->styles;
    }

    public function getScripts(): array
    {
        return $this->scripts;
    }

    // -------------------------------------------------
    // VIEW DATA
    // -------------------------------------------------

    public function with(string $key, mixed $value): self
    {
        $this->viewData[$key] = $value;
        return $this;
    }

    public function getViewData(): array
    {
        return $this->viewData;
    }

    // -------------------------------------------------
    // BLOCKS
    // -------------------------------------------------

    public function withBlocks(array $blocks): self
    {
        $this->blocks = $blocks;
        return $this;
    }

    public function getBlocks(): array
    {
        return $this->blocks;
    }
}