<?php

declare(strict_types=1);

namespace roxblnfk\Contract\Router\Route;

interface RouteGroupParams extends RouteParams
{
    /**
     * @return Route[]
     */
    public function getRoutes(): array;
}
