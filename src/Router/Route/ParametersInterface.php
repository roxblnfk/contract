<?php

declare(strict_types=1);

namespace roxblnfk\Contract\Router\Route;

interface ParametersInterface
{
    public function getName(): ?string;

    public function isNameOverride(): bool;

    public function getPattern(): string;

    public function getMethods(): iterable;

    /**
     * @return bool If true then the Route should not be matched by the RouteMatcher.
     */
    public function isPassive(): bool;

    public function isGroup(): bool;

    public function getMiddlewares(): iterable;
}
