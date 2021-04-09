<?php

declare(strict_types=1);

namespace roxblnfk\Contract\Pipeline;

use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Middlewares pipeline definition
 *
 * @method iterable|MiddlewareInterface[] getPipes() List of  MiddlewareInterface
 */
interface MiddlewaresInterface extends PipelineInterface
{
    /**
     * Last handler
     */
    public function getRequestHandler(): ?RequestHandlerInterface;
}
