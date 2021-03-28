<?php

declare(strict_types=1);

namespace roxblnfk\Contract\Implementation\Router\ConfigA;

use Psr\Http\Server\MiddlewareInterface;
use roxblnfk\Contract\Router\Route\RouteInterface;

interface ConfigurableRoute extends RouteInterface
{
    public function name(?string $name): self;

    public function override(): self;

    public function pipe(MiddlewareInterface|callable|array|string $middleware, array|string $methods = null): self;

    public function ignorePipe(string $middleware, array|string $methods = null): self;

    public function do(callable|array|string $action, array $arguments = []): RouteInterface;
}
