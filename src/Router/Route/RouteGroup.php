<?php

declare(strict_types=1);

namespace roxblnfk\Contract\Router\Route;

interface RouteGroup extends RouteInterface
{
    public function getParameters(): RouteGroupParameters;
}
