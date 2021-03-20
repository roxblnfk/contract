<?php

declare(strict_types=1);

namespace roxblnfk\Contract\Router\ConfigA;

use roxblnfk\Contract\Router\Route\RouteGroup;
use roxblnfk\Contract\Router\Route\RouteInterface;

interface AbstractRoute extends ConfigurableRoute
{
    public function group(RouteInterface ...$routes): RouteGroup;
}
