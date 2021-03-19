<?php

declare(strict_types=1);

namespace roxblnfk\Contract\Router\Route;

interface RouteGroup extends Route
{
    public function getParameters(): RouteGroupParams;
}
