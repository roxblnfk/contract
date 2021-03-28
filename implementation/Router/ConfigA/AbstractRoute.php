<?php

declare(strict_types=1);

namespace roxblnfk\Contract\Implementation\Router\ConfigA;

use roxblnfk\Contract\Router\Route\RouteInterface;

final class AbstractRoute extends ConfigurableRoute
{
    public function group(RouteInterface ...$routes): RouteInterface
    {
        $this->collectRoutes($routes);
        return $this;
    }
}
