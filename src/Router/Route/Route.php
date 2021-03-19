<?php

declare(strict_types=1);

namespace roxblnfk\Contract\Router\Route;

interface Route
{
    public function getParameters(): RouteParams;
}
