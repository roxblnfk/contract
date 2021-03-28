<?php

declare(strict_types=1);

namespace roxblnfk\Contract\Implementation\Router\Config;

/**
 * @internal
 */
final class ActionDefinition
{
    public function __construct(
        /** Middleware definition */
        public mixed $definition,

        public array $arguments,
    ) {
    }
}
