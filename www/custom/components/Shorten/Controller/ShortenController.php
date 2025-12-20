<?php 

namespace Components\Shorten\Controller;

use CHK\Core\Controller;
use CHK\Core\PageContext;
use CHK\Validator\UrlValidator;

final class ShortenController extends Controller
{
    public function resolve(array $params): string
{
    $slug = $params['slug'] ?? null;

    $repo = $this->service('shortRepo');
    $entry = $slug ? $repo->findBySlug($slug) : null;

    if (!$entry) {
        // sauber: ErrorController rendern lassen
        $error = new \CHK\Controller\ErrorController($this->app);
        return $error->error404();
    }

    $repo->incrementHits((int)$entry['id']);
    $this->redirect($entry['target_url'], 301);

    // unreachable, aber sauber
    return '';
}

    public function create(array $params = []): string
    {
        $url = trim($_POST['url'] ?? '');

        $page = ($this->getPage())
->withGlobals()
            ->withMeta(
                'URL verkürzen | 2chk',
                'Kurze Links erstellen und sicher teilen.'
            )
            
->addStyle('brand')
->addStyle('footer')
->addStyle('modals')

->addStyle('module')
->addStyle('messages')
->addStyle('footer_extra')

->addStyle('shortener')


->addScript('core')
->addScript('modals')
//->addScript('parallax')
//->addScript('shortener')
//->addScript('cookiebot')
//->addScript('recaptcha')
;

        if (!$url || !UrlValidator::isValid($url)) {
            $page->data['message'] = [
                'type'  => 'danger',
                'title' => 'Ungültige URL',
                'text'  => 'Bitte eine gültige http/https URL eingeben.'
            ];
            $page->withStatus(400);

            return $this->render('shorten.html.twig', $page);
        }

        $slugGen = $this->service('slugGenerator');
        $repo    = $this->service('shortRepo');

        do {
            $slug = $slugGen->generate();


        } while ($repo->slugExists($slug));

        $repo->create($slug, $url);

        $page->with('result', $this->config('base_url') . '/' . $slug);

        return $this->render('shorten.html.twig', $page);
    }
}