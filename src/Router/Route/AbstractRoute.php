<?php

declare(strict_types=1);

namespace roxblnfk\Contract\Router\Route;

interface AbstractRoute extends ConfigurableRoute
{
    public function group(Route ...$routes): RouteGroup;
}
