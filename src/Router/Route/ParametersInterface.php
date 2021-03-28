<?php

declare(strict_types=1);

namespace roxblnfk\Contract\Router\Route;

interface ParametersInterface
{
    public function getName(): ?string;
    public function isNameOverride(): bool;
    public function getMethods(): iterable;
    public function getPattern(): string;
    public function isGroup(): bool;

    public function getMiddlewares(): iterable;
}
