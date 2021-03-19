<?php

declare(strict_types=1);

namespace roxblnfk\Contract\Router\Route;

use Psr\Http\Server\MiddlewareInterface;

interface ConfigurableRoute extends Route
{
    public function name(?string $name): self;

    public function pipe(MiddlewareInterface|callable|array|string $middleware, array|string $methods = null): self;

    public function do(callable|array|string $action, array $arguments = []): Route;
}
