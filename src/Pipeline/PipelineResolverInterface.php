<?php

declare(strict_types=1);

namespace roxblnfk\Contract\Pipeline;

use roxblnfk\Contract\Pipeline\PipelineInterface;

interface PipelineResolverInterface
{
    public function resolvePipeline(PipelineInterface $pipeline): callable;
}
