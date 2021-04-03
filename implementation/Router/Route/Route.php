<?php

declare(strict_types=1);

namespace roxblnfk\Contract\Implementation\Router\Route;

use roxblnfk\Contract\Implementation\Router\Config\MiddlewaresSet;
use roxblnfk\Contract\Router;
use Yiisoft\Http\Method;

abstract class Route implements Router\Route\ParametersInterface, Router\Route\RouteInterface
{
    protected iterable $methods = Method::ALL;
    protected bool $isOverride = false;
    protected ?bool $isPassive = null;
    protected MiddlewaresSet $middlewares;

    private bool $isGroup = false;
    /**
     * @var Router\Route\RouteInterface[]
     */
    private ?iterable $routes = null;

    public function __construct(
        protected string $pattern,
        protected ?string $name = null,
    ) {
        $this->middlewares = new MiddlewaresSet();
    }

    final protected function collectRoutes(iterable $routes): void
    {
        $this->isGroup = true;
        $this->routes = $routes;
    }

    final public function getName(): ?string
    {
        return $this->name;
    }

    final public function getMethods(): iterable
    {
        return $this->methods;
    }

    final public function getPattern(): string
    {
        return $this->pattern;
    }

    final public function isNameOverride(): bool
    {
        return $this->isOverride;
    }

    final public function isGroup(): bool
    {
        return $this->isGroup;
    }

    final public function isPassive(): bool
    {
        return $this->isPassive ?? ($this->name !== null && $this->middlewares->isEmpty());
    }

    final public function getMiddlewares(): iterable
    {
        return $this->middlewares;
    }

    final public function getRoutes(): iterable
    {
        if (!$this->isGroup) {
            return [];
        }
        return $this->routes;
    }

    final public function getParameters(): self
    {
        return $this;
    }
}
