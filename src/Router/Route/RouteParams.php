<?php

declare(strict_types=1);

namespace roxblnfk\Contract\Router\Route;

interface RouteParams
{
    public function getName(): ?string;
    public function getMethods(): array;
    public function getPattern(): string;
    public function isOverride(): bool;
}
