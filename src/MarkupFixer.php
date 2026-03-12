<?php

namespace YamanHacioglu\LaravelToc;

use Masterminds\HTML5;
use RuntimeException;

class MarkupFixer
{
    use HtmlHelper;

    /**
     * @var
     */

    private HTML5 $htmlParser;

    public function __construct(?HTML5 $htmlParser = null): void
    {
        $this->htmlParser = $htmlParser ?: new HTML5();
    }

    public function fix(string $markup, int $topLevel = 1, int $depth = 6): string
    {
        if (!$this->isFullHtmlDocument($markup)) {
            $partialID = uniqid('toc_generator_');
            $markup = sprintf("<body id='%s'>%s</body>", $partialID, $markup);
        }

        $domDocument = $this->htmlParser->loadHTML($markup);
        $domDocument->preserveWhiteSpace = true; // do not clobber whitespace

        $sluggifier = new UniqueSluggifier();

        foreach ($this->traverseHeaderTags($domDocument, $topLevel, $depth) as $node) {
            if ($node->getAttribute('id')) {
                continue;
            }
            $node->setAttribute('id', $sluggifier->slugify($node->getAttribute('title') ?: $node->textContent));
        }

        return $this->htmlParser->saveHTML(
            (isset($partialID)) ? $domDocument->getElementById($partialID)->childNodes : $domDocument
        );

    }
}
