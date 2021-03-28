<?php

declare(strict_types=1);

namespace roxblnfk\Contract\Implementation\Router\Config;

/**
 * @internal
 */
final class MiddlewareDefinition
{
    public function __construct(
        /** Middleware definition */
        public mixed $definition,

        /** @var null|string[] */
        public ?array $methods = null,

        public bool $disabler = false
    ) {
    }
}
