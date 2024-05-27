<?php

namespace YamanHacioglu\LaravelToc;

use ArrayIterator;
use DOMDocument;
use DOMElement;
use DOMXPath;

trait HtmlHelper
{
    /**
     * @param $topLevel
     * @param $depth
     * @return string[]
     */

  protected function determineHeaderTags($topLevel, $depth)
  {
      $desired = range((int) $topLevel, (int) $topLevel + ((int) $depth -1));
      $allowed = [1, 2, 3, 4, 5, 6];

      return array_map(function ($val) {
          return 'h'. $val;
      }, array_intersect($desired, $allowed));
  }


    /**
     * @param $domDocument
     * @param $topLevel
     * @param $depth
     * @return ArrayIterator|DomElement[]
     */

  protected function traverseHeaderTags($domDocument, $topLevel, $depth)
  {
      $xpath = new DOMXPath($domDocument);

      $xpathQuery = sprintf(
          "//*[%s]",
          implode(' or ', array_map(function ($v) {
              return sprintf('local-name() = "%s"', $v);
          }, $this->determineHeaderTags($topLevel, $depth)))
      );

      $nodes = [];
      foreach ($xpath->query($xpathQuery) as $node) {
          $nodes[] = $node;
      }

      return new ArrayIterator($nodes);
  }

  protected function isFullHtmlDocument($markup)
  {
      return (strpos($markup, "<body")!== false && strpos($markup, "</body>")!== false);
  }
}
