<?php
namespace App\Controller;

use CHK\Core\Controller;
use CHK\Core\PageContext;

final class DemoController extends Controller
{
    public function index(): string
    {
        $page = ($this->getPage())
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
->addScript('modals')
//->addScript('parallax')
//->addScript('shortener')
//->addScript('cookiebot')
//->addScript('recaptcha')
;


        return $this->render('demo.html.twig', $page);
    }
}