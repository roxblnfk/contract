<?php

declare(strict_types=1);

namespace roxblnfk\Contract\Implementation\Router\ConfigB;

use Psr\Http\Server\MiddlewareInterface;
use roxblnfk\Contract\Router\Route\RouteInterface;

interface ConfigurableRoute extends RouteInterface, PipeableInterface
{
    public function name(?string $name): self;

    public function override(): self;
}
