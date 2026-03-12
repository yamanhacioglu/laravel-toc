<?php


namespace YamanHacioglu\LaravelToc;

use Knp\Menu\ItemInterface;
use Knp\Menu\Matcher\Matcher;
use Knp\Menu\MenuFactory;
use Knp\Menu\MenuItem;
use Knp\Menu\Renderer\ListRenderer;
use Knp\Menu\Renderer\RendererInterface;
use Masterminds\HTML5;
use YamanHacioglu\LaravelToc\HtmlHelper;

class Table {
    use HtmlHelper;

    private HTML5 $domParser;

    private MenuFactory $menuFactory;


    public function __construct(?MenuFactory $menuFactory = null, ?HTML5 $htmlParser = null): void
    {
        $this->domParser   = $htmlParser  ?: new HTML5();
        $this->menuFactory = $menuFactory ?: new MenuFactory();
    }

    public function getMenu(string $markup, int $topLevel = 1, int $depth = 6): ItemInterface
    {
        $menu = $this->menuFactory->createItem('TOC');

        if(trim($markup) == '') {
            return $menu;
        }

        $tagsToMatch = $this->determineHeaderTags($topLevel, $depth);

        $lastElem = $menu;

        $domDocument = $this->domParser->loadHTML($markup);
        foreach ($this->traverseHeaderTags($domDocument, $topLevel, $depth) as $node) {
            if(!$node->hasAttribute('id')) {
                continue;
        }
            $tagName = $node->tagName;
            $level = array_search(strtolower($tagName), $tagsToMatch) + 1;

            if($level == 1) {
            $parent = $menu;
            } elseif($level == $lastElem->getLevel()) {
                $parent = $lastElem->getParent();
            } elseif($level > $lastElem->getLevel()) {
                $parent = $lastElem;
                for($i = $lastElem->getLevel(); $i < ($level - 1); $i++) {
                    $parent = $parent->addChild('');
                }
            } else {
                $parent = $lastElem->getParent();
                while($parent->getLevel() > $level - 1) {
                    $parent = $parent->getParent();
                }
            }

            $lastElem = $parent->addChild(
                $node->getAttribute('id'),
                [
                    'label' => $node->getAttribute('title')?: $node->textContent,
                    'uri' => '#'. $node->getAttribute('id')
                ]
            );
        }
        return $menu;
    }

    public function getTableContent(string $markup, int $topLevel = 1, int $depth = 6, ?RendererInterface $renderer = null): string
    {
        if(!$renderer) {
            $renderer = new ListRenderer(new Matcher(), [
                'currentClass' => 'active',
                'ancestorClass' => 'active_ancestor'
            ]);
        }

        return $renderer->render($this->getMenu($markup, $topLevel, $depth));
    }
}
