<?php
namespace CHK\Core;

/**
 * PageContext
 *
 * Immutable-like state container for a single page request.
 *
 * Responsibilities:
 * - Holds page-related state (status, meta)
 * - Collects assets (styles & scripts)
 * - Transports view data
 * - Stores block configuration for rendering
 *
 * Important:
 * - PageContext contains NO rendering logic
 * - PageContext contains NO business logic
 * - PageContext is only mutated explicitly by controllers
 */
final class PageContext
{
    // -------------------------------------------------
    // STATUS & META
    // -------------------------------------------------

    /** HTTP status code */
    public int $status = 200;

    /** Document title */
    public string $title = '';

    /** Meta description */
    public ?string $description = null;

    // -------------------------------------------------
    // VIEW DATA
    // -------------------------------------------------

    /** @var array<string, mixed> */
    private array $viewData = [];

    // -------------------------------------------------
    // ASSETS (BC-LAYER)
    // -------------------------------------------------

    /** @var string[] */
    private array $styles = [];

    /** @var string[] */
    private array $scripts = [];

    // -------------------------------------------------
    // BLOCK CONFIGURATION
    // -------------------------------------------------

    /** @var array<int, array> */
    private array $blocks = [];

    // -------------------------------------------------
    // STATUS & META
    // -------------------------------------------------

    /**
     * Set HTTP status code.
     */
    public function withStatus(int $status): self
    {
        $this->status = $status;
        return $this;
    }

    /**
     * Attach globally required styles & scripts.
     *
     * Purpose:
     * - Prevents asset copy-paste in controllers
     * - Ensures error & fallback pages are correctly styled
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

    /**
     * Set document meta data.
     */
    public function withMeta(string $title, ?string $description = null): self
    {
        $this->title       = $title;
        $this->description = $description;
        return $this;
    }

    // -------------------------------------------------
    // ASSETS – BACKWARD COMPATIBLE API
    // -------------------------------------------------

    /**
     * Register a style handle.
     */
    public function addStyle(string $name): self
    {
        if (!in_array($name, $this->styles, true)) {
            $this->styles[] = $name;
        }
        return $this;
    }

    /**
     * Register a script handle.
     */
    public function addScript(string $name): self
    {
        if (!in_array($name, $this->scripts, true)) {
            $this->scripts[] = $name;
        }
        return $this;
    }

    // -------------------------------------------------
    // ASSETS – CONSUMED BY RENDERER
    // -------------------------------------------------

    /**
     * @return string[]
     */
    public function getStyles(): array
    {
        return $this->styles;
    }

    /**
     * @return string[]
     */
    public function getScripts(): array
    {
        return $this->scripts;
    }

    // -------------------------------------------------
    // VIEW DATA
    // -------------------------------------------------

    /**
     * Attach arbitrary view data.
     */
    public function with(string $key, mixed $value): self
    {
        $this->viewData[$key] = $value;
        return $this;
    }

    /**
     * @return array<string, mixed>
     */
    public function getViewData(): array
    {
        return $this->viewData;
    }

    // -------------------------------------------------
    // BLOCKS
    // -------------------------------------------------

    /**
     * Attach block configuration.
     *
     * @param array<int, array> $blocks
     */
    public function withBlocks(array $blocks): self
    {
        $this->blocks = $blocks;
        return $this;
    }

    /**
     * @return array<int, array>
     */
    public function getBlocks(): array
    {
        return $this->blocks;
    }
}