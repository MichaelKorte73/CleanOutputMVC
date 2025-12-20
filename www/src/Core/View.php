<?php
namespace CHK\Core;

use Twig\Environment;

final class View
{
    private Environment $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    public function render(
    string $template,
    PageContext $page,
    array $extra = []
): string {
    return $this->twig->render($template, array_merge(
        $page->getViewData(),
        [
            'page' => $page,
        ],
        $extra
    ));
}
}