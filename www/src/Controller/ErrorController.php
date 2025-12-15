<?php
/**
 * Clean Output MVC
 *
 * Error Controller
 *
 * Handles HTTP error responses (currently 404).
 * Uses the standard rendering pipeline with PageContext and Renderer.
 *
 * @author    Michael Korte
 * @email     mkorte@korte-software.de
 * @company   Michael Korte Software
 *
 * @version   0.1
 * @date      13.12.2025
 *
 * @package   CHK\Controller
 */

namespace CHK\Controller;

use CHK\Core\Controller;

final class ErrorController extends Controller
{
    /**
     * Render 404 error page.
     *
     * @return string Rendered HTML output
     */
    public function error404(): string
    {
        $page = $this->getPage()
            ->withGlobals()
            ->withStatus(404)
            ->withMeta(
                '404 | 2chk',
                'Seite nicht gefunden.'
            )
            ->addStyle('brand')
            ->addStyle('footer')
            ->addStyle('modals')
            ->addStyle('module')
            ->addStyle('messages')
            ->addStyle('footer_extra')
            ->addStyle('home')
            ->addStyle('shortener')
            ->addScript('modals');
            // Optional scripts (currently disabled):
            // ->addScript('parallax')
            // ->addScript('shortener')
            // ->addScript('cookiebot')
            // ->addScript('recaptcha');

        return $this->render('error/404.html.twig', $page);
    }
}