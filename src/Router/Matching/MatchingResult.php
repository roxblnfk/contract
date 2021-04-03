<?php

declare(strict_types=1);

namespace roxblnfk\Contract\Router\Matching;

use roxblnfk\Contract\Pipeline\PipelineInterface;
use Stringable;

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

    /**
     * Generate new URL based on matched Route and Route params
     *
     * @param iterable $params Params to merge with current params
     * @param iterable $query GET parameters
     */
    public function generateUrl(iterable $params = [], iterable $query = []): string|Stringable;

    /**
     * Full route pipeline for matched Uri
     */
    public function getPipeline(): PipelineInterface;
}
