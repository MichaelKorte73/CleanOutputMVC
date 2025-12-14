# Clean-Output-MVC

> **Status:** v0.1 (Foundation)  
> **Focus:** Clean HTML Output · Performance · Accessibility · SEO  
> **Not a CMS. Not a plugin platform. Not a feature collection.**

---

## What is this?

**Clean-Output-MVC** is a small, architecture-driven MVC framework  
for projects where **output quality matters more than feature breadth**.

It is built around a simple idea:

> **HTML is not a side effect – it is the product.**

The framework is intentionally strict to ensure:

- predictable rendering  
- clean, semantic HTML  
- performance by default  
- accessibility by structure  
- explainable, testable behavior  

---

## What problem does it solve?

Many modern frameworks and CMS are excellent at handling **complex workflows**,  
but often treat HTML output as an indirect result of layers, plugins and abstractions.

Clean-Output-MVC focuses on a **much narrower problem space**:

- small to mid-sized sites  
- performance-critical pages  
- technically controlled layouts  
- SEO- and accessibility-driven projects  
- long-term maintainability over rapid feature growth  

---

## Why another framework?

There are many **very good and well-designed systems** out there.

Frameworks like **Symfony** or **Laravel** provide powerful abstractions and ecosystems.  
CMS solutions like **WordPress** or **TYPO3** solve real editorial problems at scale.

**Clean-Output-MVC does not try to replace them.**

### What those systems are great at

- rich feature sets  
- plugins and extensions  
- editorial workflows  
- rapid development across many use cases  

### Where they often struggle (by design)

- HTML output becomes indirect and hard to reason about  
- performance and accessibility are often retrofitted  
- rendering pipelines grow implicit and complex  
- output quality depends on discipline, not structure  

### What Clean-Output-MVC does instead

- treats **HTML as the primary product**  
- enforces a **deterministic render pipeline**  
- separates state, rendering and assets strictly  
- avoids plugin magic and hidden control flow  
- stays small, explicit and predictable  

> This framework is not *better*.  
> It is **smaller, stricter and more focused**.

---

## What this is **not**

- ❌ not a CMS  
- ❌ not a page builder  
- ❌ not a plugin ecosystem  
- ❌ not designed for editorial freedom  

If you need flexible content workflows or plugins,  
a CMS is the right choice.

---

## Core principles

- **Core is infrastructure**  
  Controllers, templates, blocks and JS modules are guests.

- **Explicit over magic**  
  No hidden defaults. No guess APIs.

- **Deterministic rendering**  
  One clear lifecycle. One output path.

- **Stability before comfort**  
  Architecture first. Tools later.

---

## Who should use this?

- Developers with architectural responsibility  
- Freelancers and agencies building controlled sites  
- Performance- and accessibility-focused projects  
- Teams that prefer structure over convenience  

---

## Status

- Architecture: **stable**  
- Block system: **active**  
- Security layer: **active**  
- JS lifecycle: **fixed**  
- Version: **v0.1 (Foundation)**  

The framework is intentionally small.  
Future features will build **on top of this architecture**, not around it.

---

## Documentation

Full architecture documentation lives outside this README  
and explains the design decisions in detail.

> This README sets expectations.  
> The documentation explains the system.
> 
