<?php

declare(strict_types=1);

namespace roxblnfk\Contract\Implementation\Pipeline;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use roxblnfk\Contract\Implementation\Pipeline\Exception\DefaultRequestHandlerIsNotConfiguredException;
use roxblnfk\Contract\Implementation\Pipeline\Exception\InvalidMiddlewareDefinitionException;
use roxblnfk\Contract\Pipeline\MiddlewaresPipelineInterface;
use roxblnfk\Contract\Pipeline\PipelineInterface;
use roxblnfk\Contract\Pipeline\PipelineResolverInterface;

final class MiddlewaresPipelineResolver implements PipelineResolverInterface
{
    private ContainerInterface $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }
    public function resolvePipeline(PipelineInterface ...$pipelines): callable
    {
        $iterator = $this->iteratePipelines($pipelines);
        return new GlueRequestHandler($iterator);
    }

    /**
     * @param PipelineInterface[] $pipelines
     */
    private function iteratePipelines(array $pipelines): \Generator
    {
        foreach ($pipelines as $pipeLine) {
            foreach ($pipeLine->getPipes() as $pipeDefinition) {
                yield $this->resolveMiddlewareDefinition($pipeDefinition);
            }
        }
        return $this->getRequestHandler($pipeLine ?? null);
    }

    private function getRequestHandler(?PipelineInterface $pipeline): ?RequestHandlerInterface
    {
        $handler = $pipeline instanceof MiddlewaresPipelineInterface ? $pipeline->getRequestHandler() : null;
        return $handler ?? $this->getDefaultRequestHandler();
    }

    private function getDefaultRequestHandler(): RequestHandlerInterface
    {

        throw new DefaultRequestHandlerIsNotConfiguredException();
    }

    private function resolveMiddlewareDefinition(mixed $definition): MiddlewareInterface|callable
    {
        if ($definition instanceof MiddlewareInterface || is_callable($definition)) {
            return $definition;
        }
        if (is_string($definition)) {
            return $this->container->get($definition);
        }
        if (is_array($definition) && array_keys($definition) === [0, 1]) {
            return [$this->container->get($definition[0]), $definition[1]];
        }
        throw new InvalidMiddlewareDefinitionException(
            sprintf('Middleware MUST return instance of %s.', ResponseInterface::class)
        );
    }
}
