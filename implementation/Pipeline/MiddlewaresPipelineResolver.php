<?php

declare(strict_types=1);

namespace roxblnfk\Contract\Implementation\Pipeline;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use roxblnfk\Contract\Implementation\Pipeline\Exception\DefaultRequestHandlerIsNotConfiguredException;
use roxblnfk\Contract\Implementation\Pipeline\Exception\InvalidMiddlewareDefinitionException;
use roxblnfk\Contract\Pipeline\MiddlewaresPipelineInterface;
use roxblnfk\Contract\Pipeline\PipelineInterface;
use roxblnfk\Contract\Pipeline\PipelineResolverInterface;
use Yiisoft\Injector\Injector;

final class MiddlewaresPipelineResolver implements PipelineResolverInterface
{
    private Injector $injector;
    private ?RequestHandlerInterface $defaultRequestHandler = null;

    public function __construct(Injector $injector)
    {
        $this->injector = $injector;
    }

    public function withDefaultRequestHandler(?RequestHandlerInterface $handler): self
    {
        $new = clone $this;
        $new->defaultRequestHandler = $handler;
        return $new;
    }

    public function resolvePipeline(PipelineInterface|iterable ...$pipelines): callable
    {
        $iterator = $this->iteratePipelines($pipelines);
        return $this->createHandler($iterator);
    }

    private function createHandler(\Generator $iterator): callable
    {
        return new class ($iterator, $this->injector) implements RequestHandlerInterface
        {
            private Injector $injector;
            private \Generator $iterator;

            public function __construct(\Generator $iterator, Injector $injector)
            {
                $this->iterator = $iterator;
                $this->injector = $injector;
            }

            final public function __invoke(ServerRequestInterface $request): ResponseInterface
            {
                return $this->handle($request);
            }

            final public function handle(ServerRequestInterface $request): ResponseInterface
            {
                if (!$this->iterator->valid()) {
                    /** @var RequestHandlerInterface $handler */
                    $handler = $this->iterator->getReturn();
                    return $handler->handle($request);
                }
                $middleware = $this->iterator->current();
                $this->iterator->next();
                $nextHandler = new self($this->iterator, $this->injector);

                if ($middleware instanceof MiddlewareInterface) {
                    return $middleware->process($request, $nextHandler);
                }
                if (is_callable($middleware)) {
                    $result = $this->injector->invoke($middleware, [$request, $nextHandler]);
                    // Kostyl' for yiisoft/router
                    if ($result instanceof MiddlewareInterface) {
                        return $result->process($request, $nextHandler);
                    }
                } else {
                    throw new InvalidMiddlewareDefinitionException();
                }
                if (!$result instanceof ResponseInterface) {
                    throw new InvalidMiddlewareDefinitionException(
                        sprintf('Middleware MUST return instance of %s.', ResponseInterface::class)
                    );
                }
                return $result;
            }
        };
    }

    /**
     * @param array<mixed, PipelineInterface|iterable> $pipelines
     *
     * @throws InvalidMiddlewareDefinitionException
     */
    private function iteratePipelines(array $pipelines): \Generator
    {
        foreach ($pipelines as $pipeLine) {
            $definitions = $pipeLine instanceof PipelineInterface ? $pipeLine->getPipes() : $pipeLine;
            foreach ($definitions as $pipeDefinition) {
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
        if ($this->defaultRequestHandler !== null) {
            return $this->defaultRequestHandler;
        }
        throw new DefaultRequestHandlerIsNotConfiguredException();
    }

    private function resolveMiddlewareDefinition(mixed $definition): MiddlewareInterface|callable
    {
        if ($definition instanceof MiddlewareInterface || is_callable($definition)) {
            return $definition;
        }
        if (is_string($definition)) {
            return $this->injector->make($definition);
        }
        if (is_array($definition) && array_keys($definition) === [0, 1]) {
            return [$this->injector->make($definition[0]), $definition[1]];
        }
        throw new InvalidMiddlewareDefinitionException(
            sprintf('Middleware MUST return instance of %s.', ResponseInterface::class)
        );
    }
}