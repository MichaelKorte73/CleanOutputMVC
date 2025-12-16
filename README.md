# Clean Output MVC

**Status:** v0.1 (Work in Progress)  
**Focus:** clean HTML output, performance, accessibility, SEO

Clean Output MVC is a deliberately minimal MVC framework for projects
where **output quality matters more than feature density**.

It is not a CMS, not a page builder, and not a plugin ecosystem.
It is a framework for developers who want full control over
what is rendered, when it is rendered, and why.

---

> 📘 **Tutorial (Work in Progress)**  
> A detailed step-by-step introduction to Clean-Output-MVC is available here:  
> **https://korte-software.de/tutorial/clean-output-mvc/teil-1**  
>  
> ⚠️ Note: The tutorial is currently **available in German only** and is **still under construction**.  
> Content will be expanded and refined continuously.

---

## What problem does it solve?

Many modern frameworks are excellent systems — but they are rarely
designed from the **output backwards**.

HTML often becomes a side effect of:
- feature abstractions
- editor workflows
- plugin logic
- implicit defaults

Clean Output MVC starts from the opposite direction:

> HTML is the product.  
> Everything else is infrastructure.

---

## What this framework is

- A small, explicit MVC framework
- Server-side rendering by default
- Deterministic rendering pipeline
- Explicit asset handling (CSS & JS)
- Performance- and accessibility-oriented by design
- Strict architecture with controlled extension points

Typical use cases:
- performance-critical websites
- technical landing pages
- content-heavy sites with strict semantics
- projects where SEO and accessibility are non-negotiable

---

## What this framework is not

- Not a CMS
- Not a low-code tool
- Not a rapid MVP generator
- Not a plugin marketplace
- Not a SPA framework

If you need:
- visual editors
- free-form layouts
- client-side application state

then this is likely **not** the right tool.

---

## Architectural highlights (short version)

- **Controllers describe pages**, not HTML
- **PageContext holds page state**, no rendering logic
- **Renderer orchestrates output**, assets and templates
- **Twig renders pure HTML**
- **Blocks define structure**, not editors
- **JavaScript is enhancement**, not an app layer
- **Services are optional** and can be disabled gracefully
- **Security happens before business logic**

The architecture is strict by default —  
but allows **explicit, documented deviations when necessary**.

---

## Comparison to existing systems

There are excellent frameworks out there:
Symfony, Laravel, WordPress, TYPO3, and many others.

Clean Output MVC does not compete with them feature by feature.

Its trade-off is different:

| Focus | Typical frameworks | Clean Output MVC |
|-----|--------------------|------------------|
| Features | very high | deliberately limited |
| Output control | flexible | explicit |
| Performance | optimizable | default |
| Accessibility | optional | built-in mindset |
| Magic | common | avoided |

This framework exists for projects where
**small, clean, fast** is more important than convenience.

---

## Status & roadmap

- v0.1: architectural foundation (stable)
- No CMS features
- No admin backend
- No automatic validation pipeline

Future versions may add:
- system-level analysis tools (SEO, accessibility)
- optional admin or tooling components
- image and media processing services

Only if they do not break the core principles.

---

## Philosophy (one sentence)

> Strict by default.  
> Explicit by design.  
> Flexible only when justified.

---

## License

MIT
