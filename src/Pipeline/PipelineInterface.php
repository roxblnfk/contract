<?php

declare(strict_types=1);

namespace roxblnfk\Contract\Pipeline;

/**
 * Pipeline definition
 */
interface PipelineInterface
{
    /**
     * Get list of pipe definitions
     */
    public function getPipes(): iterable;
}
