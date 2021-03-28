<?php

declare(strict_types=1);

namespace roxblnfk\Contract\Router\Route;

interface RouteInterface
{
    public function getParameters(): ParametersInterface;
}
