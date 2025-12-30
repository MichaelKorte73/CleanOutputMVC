<?php
declare(strict_types=1);

/**
 * Clean Output MVC
 *
 * Error Controller
 *
 * Controller zur Ausgabe von Fehlerseiten.
 *
 * Aufgabe:
 * - Liefert standardisierte Error-Responses
 * - Baut PageContext für Fehlerzustände
 *
 * ❗ HINWEIS:
 * - Dieser Controller ist **app-/projektbezogen**
 * - Styles & Scripts sind bewusst konkret
 * - Keine generische Core-Error-Seite
 *
 * Der Core stellt nur den Mechanismus,
 * nicht das Design.
 *
 * @package   CHK\Controller
 * @author    Michael Korte
 * @license   MIT
 */

namespace CHK\Controller;

use CHK\Core\Controller;
use CHK\Core\PageContext;

final class ErrorController extends Controller
{
    /**
     * 404 – Seite nicht gefunden.
     *
     * @return string
     */
    public function error404(): string
    {
        $page = ($this->getPage())
            ->withGlobals()
            ->withStatus(404)
            ->withMeta('404 | 2chk', 'Seite nicht gefunden.')
            ->addStyle('brand')
            ->addStyle('footer')
            ->addStyle('modals')
            ->addStyle('module')
            ->addStyle('messages')
            ->addStyle('footer_extra')
            ->addStyle('home')
            ->addStyle('shortener')
            ->addScript('modals');
            // ->addScript('parallax')
            // ->addScript('shortener')
            // ->addScript('cookiebot')
            // ->addScript('recaptcha')

        return $this->render('error/404.html.twig', $page);
    }
}