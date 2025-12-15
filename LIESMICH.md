# Clean Output MVC

**Status:** v0.1 (Work in Progress)  
**Fokus:** sauberer HTML-Output, Performance, Accessibility, SEO

Clean Output MVC ist ein bewusst reduziertes MVC-Framework
für Projekte, bei denen **Output-Qualität wichtiger ist als Feature-Masse**.

Es ist kein CMS, kein Baukasten und kein Plugin-Ökosystem.
Es richtet sich an Entwickler:innen, die kontrollieren wollen,
was ausgeliefert wird – und warum.

---

## Welches Problem wird gelöst?

Viele moderne Frameworks sind hervorragende Systeme –
aber sie denken selten konsequent **vom Output her**.

HTML entsteht dort oft als Nebenprodukt von:
- Feature-Abstraktionen
- Editor-Workflows
- Plugin-Logik
- impliziten Defaults

Clean Output MVC dreht das um:

> HTML ist das Produkt.  
> Alles andere ist Infrastruktur.

---

## Was dieses Framework ist

- Ein kleines, explizites MVC-Framework
- Server-Side Rendering als Standard
- deterministische Render-Pipeline
- bewusstes Asset-Handling (CSS & JS)
- Performance & Accessibility by design
- strenge Architektur mit klaren Erweiterungspunkten

Typische Einsatzszenarien:
- performance-kritische Websites
- technische Landingpages
- inhaltsstarke Seiten mit sauberer Semantik
- Projekte mit hohem SEO- und Accessibility-Anspruch

---

## Was dieses Framework nicht ist

- Kein CMS
- Kein Low-Code-Tool
- Kein Rapid-MVP-Generator
- Kein Plugin-Marktplatz
- Kein SPA-Framework

Wenn du brauchst:
- visuelle Editoren
- freie Layouts
- clientseitigen App-State

ist dieses Framework **nicht** die richtige Wahl.

---

## Architektur in Kürze

- **Controller beschreiben Seiten**, nicht HTML
- **PageContext hält Seitenzustand**, ohne Logik
- **Renderer orchestriert Ausgabe**, Assets und Templates
- **Twig rendert reines HTML**
- **Blöcke definieren Struktur**, nicht Redaktion
- **JavaScript ist Enhancement**, kein App-Layer
- **Services sind optional** und dürfen fehlen
- **Security passiert vor der Business-Logik**

Die Architektur ist bewusst streng –
aber erlaubt **explizite, dokumentierte Abweichungen**, wenn nötig.

---

## Einordnung im Vergleich

Es gibt sehr gute, durchdachte Systeme:
Symfony, Laravel, WordPress, TYPO3 u. a.

Clean Output MVC will diese Systeme nicht ersetzen.

Der Fokus ist ein anderer:

| Fokus | Klassische Frameworks | Clean Output MVC |
|-----|-----------------------|------------------|
| Feature-Tiefe | hoch | bewusst gering |
| Output-Kontrolle | flexibel | explizit |
| Performance | optimierbar | Default |
| Accessibility | optional | mitgedacht |
| Magie | häufig | vermieden |

Dieses Framework existiert für Projekte,
bei denen **klein, sauber und schnell** wichtiger ist als Komfort.

---

## Status & Ausblick

- v0.1: Architektur-Fundament (stabil)
- kein CMS
- kein Admin-Backend
- keine automatische Validierung

Spätere Versionen **können** beinhalten:
- systemnahe Analyse-Tools (SEO, Accessibility)
- optionale Admin- oder Tool-Components
- Media- & Image-Processing

Nur, wenn die Grundprinzipien erhalten bleiben.

---

## Philosophie in einem Satz

> Streng im Kern.  
> Explizit im Design.  
> Flexibel nur mit Begründung.

---

## Lizenz

MIT