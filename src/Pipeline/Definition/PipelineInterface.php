<?php

declare(strict_types=1);

namespace roxblnfk\Contract\Pipeline\Definition;

use IteratorAggregate;

/**
 * Pipeline definition
 */
interface PipelineInterface extends IteratorAggregate
{
    /**
     * Get list of pipe definitions
     */
    public function getPipes(): iterable;
}
