<?php
namespace CHK\Core;

use CHK\Media\StyleRenderer;
use CHK\Media\ScriptRenderer;

final class Renderer
{
    public function __construct(
        private App $app
    ) {}

    public function render(string $template, PageContext $page): string
    {
        Hooks::doAction('renderer.before', $page);

        /** @var StyleRenderer $styles */
        $styles = $this->app->getService('styleRenderer');

        /** @var ScriptRenderer $scripts */
        $scripts = $this->app->getService('scriptRenderer');


$scripts_top=$scripts->renderGroup('top',$page->getScripts());
$scripts_head=$scripts->renderGroup('head',$page->getScripts());
$scripts_footer=$scripts->renderGroup('footer',$page->getScripts());

   /** @var BlockRenderer $blockRenderer */
$blockRenderer = $this->app->getService('blockRenderer');

$content = $blockRenderer->render(
    $page->getBlocks()
);             
        


        $html = $this->app->getService('view')->render(
            $template,
            $page,
            [
                'styles'  => $styles->render($page->getStyles()),
   'scripts_top' => $scripts_top,
   'scripts_head' => $scripts_head,
                'scripts_footer' => $scripts_footer,
'content'=>$content,
            ]
        );

        Hooks::doAction('renderer.after', $page, $html);

        return $html;
    }
}