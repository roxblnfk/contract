<?php

declare(strict_types=1);

namespace roxblnfk\Contract\Router\Route;

use Psr\Http\Server\MiddlewareInterface;

interface RouteParameters
{
    public function getName(): ?string;
    public function getMethods(): iterable;
    public function getPattern(): string;
    public function isOverride(): bool;
    /**
     * todo
     * @return MiddlewareInterface[]
     */
    public function getMiddlewares(): iterable;
}
