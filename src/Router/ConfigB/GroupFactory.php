<?php

declare(strict_types=1);

namespace roxblnfk\Contract\Router\ConfigB;

class GroupFactory
{
    private function __construct()
    {}

    /**
     * @param RouteFactory[] $routes
     */
    public static function create(string $pattern, array $routes): ConfigurableRouteGroup
    {}
}
