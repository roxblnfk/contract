<?php

declare(strict_types=1);

namespace roxblnfk\Contract\Pipeline;

interface PipelineResolverInterface
{
    public function resolvePipeline(PipelineInterface ...$pipelines): callable;
}
