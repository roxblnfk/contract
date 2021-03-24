<?php

declare(strict_types=1);

namespace roxblnfk\Contract\Router\Route;

interface RouteGroupParameters extends RouteParameters
{
    /**
     * @return RouteInterface[]
     */
    public function getRoutes(): iterable;
}
