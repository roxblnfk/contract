<?php

declare(strict_types=1);

namespace roxblnfk\Contract\Router\Matching;

use roxblnfk\Contract\Pipeline\PipelineDefinitionInterface;

interface MatchingResult
{
    /**
     * Route name
     */
    public function getName(): ?string;

    /**
     * Full route pattern
     */
    public function getPattern(): ?string;

    public function getUrl(): string;

    public function getMethod(): string;

    // todo
    public function generateUrl(iterable $params = []): string;

    /**
     * Full route pipeline for matched Uri
     */
    public function getPipeline(): PipelineDefinitionInterface;
}
