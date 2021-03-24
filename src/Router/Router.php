<?php

declare(strict_types=1);

namespace roxblnfk\Contract\Router;

use roxblnfk\Contract\Router\Matching\UrlMatcherInterface;

class Router implements RouterInterface
{
    public static function create(iterable $routes): self
    {

    }

    public function getMatcher(): UrlMatcherInterface
    {
        // TODO: Implement getMatcher() method.
    }
}
