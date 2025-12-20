<?php
namespace CHK\Core;

final class PageContext
{
    // -------------------------------------------------
    // STATUS / META
    // -------------------------------------------------
    public int $status = 200;
    public string $title = '';
    public ?string $description = null;

    // -------------------------------------------------
    // VIEW DATA
    // -------------------------------------------------
    private array $viewData = [];

    // -------------------------------------------------
    // ASSETS (BC-LAYER)
    // -------------------------------------------------
    private array $styles  = [];
    private array $scripts = [];

    // -------------------------------------------------
    // META
    // -------------------------------------------------
    public function withStatus(int $status): self
    {
        $this->status = $status;
        return $this;
    }
    public function withGlobals(){
        $this->addStyle('reset')
->addStyle('base')
->addStyle('typography')
->addStyle('grid')
->addStyle('layout')
->addStyle('helpers')
->addScript('core')
;

        return $this;
    } 
    public function withMeta(string $title, ?string $description = null): self
    {
        $this->title       = $title;
        $this->description = $description;
        return $this;
    }

    // -------------------------------------------------
    // ASSETS – BACKWARD COMPATIBLE API
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

    // -------------------------------------------------
    // ASSETS – FOR RENDERER
    // -------------------------------------------------
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

// PageContext
public function withBlocks(array $blocks): self
{
    $this->blocks = $blocks;
    return $this;
}

public function getBlocks(): array
{
    return $this->blocks ?? [];
}
}