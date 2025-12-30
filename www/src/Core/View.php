<?php
declare(strict_types=1);

/**
 * Clean Output MVC
 *
 * View
 *
 * Dünner Twig-Wrapper für klassische Template-Renderings.
 *
 * Verantwortlich für:
 * - Übergabe des PageContext an Twig
 * - Zusammenführen von View-Daten
 *
 * ❗ Keine Business-Logik
 * ❗ Keine Asset-Logik
 * ❗ Kein HTML-Zusammenbau
 *
 * Der View kennt ausschließlich:
 * - das Template
 * - den PageContext
 * - optionale Zusatzdaten
 *
 * @package   CHK\Core
 * @author    Michael Korte
 * @license   MIT
 */

namespace CHK\Core;

use Twig\Environment;

final class View
{
    private Environment $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    /**
     * Rendert ein Twig-Template.
     *
     * Standardmäßig verfügbar im Template:
     * - Alle Werte aus PageContext::getViewData()
     * - Variable `page` mit dem kompletten PageContext
     *
     * @param string      $template  Twig-Template-Pfad
     * @param PageContext $page      Aktueller PageContext
     * @param array       $extra     Optionale Zusatzdaten
     *
     * @return string HTML
     */
    public function render(
        string $template,
        PageContext $page,
        array $extra = []
    ): string {
        return $this->twig->render(
            $template,
            array_merge(
                $page->getViewData(),
                [
                    'page' => $page,
                ],
                $extra
            )
        );
    }
}