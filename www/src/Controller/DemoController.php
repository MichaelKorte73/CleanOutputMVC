<?php
declare(strict_types=1);

/**
 * Clean Output MVC
 *
 * Demo Controller (Example)
 *
 * Beispiel-Controller zur Demonstration
 * des block-basierten Renderings.
 *
 * Aufgabe:
 * - Zeigt die Nutzung von PageContext + Blocks
 * - Enthält bewusst KEIN HTML
 * - Enthält KEINE Rendering-Logik
 *
 * ❗ HINWEIS:
 * - Dies ist ein Demo-/Beispiel-Controller
 * - Gehört nicht dauerhaft in den Core
 * - Wird perspektivisch nach /App verschoben
 *
 * @package   CHK\Controller
 * @author    Michael Korte
 * @license   MIT
 */

namespace CHK\Controller;

use CHK\Core\Controller;

final class DemoController extends Controller
{
    /**
     * Rendert eine Demo-Seite mit Beispiel-Blocks.
     *
     * @return string
     */
    public function index(): string
    {
        $page = $this->getPage()
            ->withGlobals()
            ->withMeta(
                'DEMO 2chk – Tools to check & shorten URLs',
                'DEMO 2chk ist eine Sammlung kleiner Webtools rund um URLs: verkürzen, prüfen, analysieren.'
            )
            ->withBlocks([
                [
                    'type' => 'hero',
                    'data' => [
                        'headline' => 'Tools für saubere URLs',
                        'text'     => 'Prüfen, kürzen, analysieren.',
                        'image'    => [
                            'preset' => 'hero',
                            'file'   => 'hero',
                            'alt'    => 'Hero Hintergrundbild',
                        ],
                    ],
                ],
                [
                    'type' => 'text',
                    'data' => [
                        'title'   => 'Hallo Welt',
                        'content' => '<p>Block-Test</p>',
                    ],
                ],
                [
                    'type' => 'alert',
                    'data' => [
                        'level'   => 'info', // info | warning | error
                        'content' => 'Dies ist ein Hinweis.',
                    ],
                ],
                [
                    'type' => 'text_image',
                    'data' => [
                        'title' => 'Über uns',
                        'text'  => '<p>Lorem ipsum…</p>',
                        'image' => [
                            'preset' => 'content',
                            'file'   => 'hero',
                            'alt'    => 'Teamfoto',
                        ],
                        'imagePosition' => 'left', // left | right
                    ],
                ],
            ])
            ->addStyle('brand')
            ->addStyle('footer')
            ->addStyle('modals')
            ->addStyle('module')
            ->addStyle('messages')
            ->addStyle('footer_extra')
            ->addStyle('home')
            ->addStyle('shortener')
            ->addScript('core')
            ->addScript('modals');

        return $this->render('demo.html.twig', $page);
    }
}