<?php

namespace YamanHacioglu\LaravelToc;

use Cocur\Slugify\Slugify;

class UniqueSluggifier
{
    private $slugify;
    private $used;

    public function __construct(Slugify $slugify = null)
    {
        $this->used = array();
        $this->slugify = $slugify ?: new Slugify();
    }

    public function slugify($text)
    {
        $slugged = $this->slugify->slugify($text);

        $count = 1;
        $orig = $slugged;
        while (in_array($slugged, $this->used)) {
            $slugged = $orig. '-'. $count;
            $count++;
        }

        $this->used[] = $slugged;
        return $slugged;
    }
}
