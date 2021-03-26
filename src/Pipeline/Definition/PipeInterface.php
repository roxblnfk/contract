<?php

declare(strict_types=1);

namespace roxblnfk\Contract\Pipeline\Definition;

/**
 * Pipe definition
 */
interface PipeInterface
{
    public function getDefinition(): mixed;
    public function getParameters(): iterable;
}
