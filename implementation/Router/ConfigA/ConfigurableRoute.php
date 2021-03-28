<?php

declare(strict_types=1);

namespace roxblnfk\Contract\Implementation\Router\ConfigA;

use roxblnfk\Contract\Implementation\Router\Config\ActionDefinition;
use roxblnfk\Contract\Implementation\Router\Config\MiddlewareDefinition;
use roxblnfk\Contract\Implementation\Router\Route\Route;
use roxblnfk\Contract\Router\Route\RouteInterface;

class ConfigurableRoute extends Route
{
    final public function methods(?iterable $methods): self
    {
        $this->methods = $methods;
        return $this;
    }

    final public function name(?string $name): self
    {
        $this->name = $name;
        return $this;
    }

    final public function override(bool $value = true): self
    {
        $this->isOverride = $value;
        return $this;
    }

    final public function pipe(
        mixed $middleware,
        array|string $methods = null
    ): self {
        $middleware = new MiddlewareDefinition($middleware, $methods, false);
        $this->middlewares->prepend($middleware);
        return $this;
    }

    final public function ignorePipe(string $middleware, array|string $methods = null): self
    {
        $this->middlewares->prepend(new MiddlewareDefinition($middleware, $methods, true));
        return $this;
    }

    public function do(mixed $action, array $arguments = []): RouteInterface
    {
        $this->middlewares->setAction(new ActionDefinition($action, $arguments));
        return $this;
    }
}
