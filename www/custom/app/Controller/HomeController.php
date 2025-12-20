<?php
namespace App\Controller;

use CHK\Core\Controller;
use CHK\Core\PageContext;

final class HomeController extends Controller
{
    public function index(): string
    {
        $page = ($this->getPage())
->withGlobals()
            ->withMeta(
                '2chk – Tools to check & shorten URLs',
                '2chk ist eine Sammlung kleiner Webtools rund um URLs: verkürzen, prüfen, analysieren.'
            )
         
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


        return $this->render('home.html.twig', $page);
    }
}