<?php

declare(strict_types=1);

namespace roxblnfk\Contract\Router;

use roxblnfk\Contract\Router\Matching\UrlMatcherInterface;

interface RouterInterface extends UrlMatcherInterface
{
    /** todo decide: extends UrlMatcherInterface or getter */
    public function getMatcher(): UrlMatcherInterface;

    // todo
    public function getUrlGenerator();

    // todo
    public function getRouteMap();
}
