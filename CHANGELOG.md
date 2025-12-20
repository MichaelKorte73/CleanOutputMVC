# Changelog
All notable changes to this project are documented in this file.

The format is inspired by *Keep a Changelog*.
This project follows a pragmatic, architecture-first versioning approach.

---

## [0.2] – Architecture applied
**Release date:** 2025-12  
**Type:** Architectural milestone

### Added
- Explicit **App layer** as lifecycle orchestrator
- **Component system** for domain-level capabilities
- **Plugin system** for observation and cross-cutting concerns
- Deterministic routing without auto-discovery
- Explicit bootstrap process
- Central logging infrastructure with bitmask-based log levels
- Hook system for controlled extensibility
- Middleware pipeline for request guards and security
- Completed tutorial (18 parts) documenting the full architectural journey

### Changed
- Core no longer contains application logic or routes
- Controllers are strictly page orchestrators
- Rendering is fully centralized in the Renderer
- Routes are registered explicitly by App and Components
- Services are optional and may degrade gracefully if unavailable

### Removed
- Default routes from the Core
- Implicit bootstrapping and hidden initialization logic
- Any form of automatic component or plugin discovery

### Fixed
- Ambiguous rendering paths
- Inconsistent error and fallback handling
- Unclear responsibility boundaries between Controller and View

### Notes
Version 0.2 marks the point where the architecture is not only defined,
but actively enforced and used in a real project context.

---

## [0.1] – Architectural foundation
**Status:** Archived (never formally released)  
**Type:** Conceptual groundwork

### Added
- Server-side rendering as default
- Clear separation of Controller, PageContext, Renderer and View
- Block-based page structure (developer-focused, no editor)
- Twig integration
- Asset renderers for CSS, JavaScript and images
- Initial middleware structure
- Security hardening preparation
- Architectural seeds defining scope and philosophy

### Changed
- MVC interpreted with output-first priorities
- Controllers stripped of rendering responsibility

### Removed
- CMS assumptions
- Editor-centric concepts
- Implicit output generation

### Notes
Version 0.1 was a development and exploration phase.
It established terminology, constraints and architectural direction,
but was never intended for productive use.

---

## Versioning philosophy

- **0.x versions** indicate architectural evolution
- Minor version increments represent conceptual milestones
- Features are only added if they do not compromise:
  - output determinism
  - explainability
  - architectural clarity

Convenience is never allowed to undermine structure.