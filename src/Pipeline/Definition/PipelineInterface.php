<?php

declare(strict_types=1);

namespace roxblnfk\Contract\Pipeline\Definition;

/**
 * Pipeline definition
 */
interface PipelineInterface
{
    public function getPipes(): iterable;
}
