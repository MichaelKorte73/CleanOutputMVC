<?php
namespace CHK\Controller;

use CHK\Core\Controller;
use CHK\Core\PageContext;

final class ErrorController extends Controller
{
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
->addScript('modals')
//->addScript('parallax')
//->addScript('shortener')
//->addScript('cookiebot')
//->addScript('recaptcha')
;

        return $this->render('error/404.html.twig', $page);
    }
}