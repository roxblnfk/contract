<?php

declare(strict_types=1);

namespace roxblnfk\Contract\Implementation\Pipeline;

use Generator;
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
use Yiisoft\Injector\Injector;

final class MiddlewaresPipelineResolver implements PipelineResolverInterface
{
    private ContainerInterface $container;
    private Injector $injector;
    private ?RequestHandlerInterface $defaultRequestHandler = null;

    public function __construct(ContainerInterface $container, Injector $injector)
    {
        $this->injector = $injector;
        $this->container = $container;
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

    private function createHandler(Generator $iterator): callable
    {
        return new class ($iterator, $this->injector) implements RequestHandlerInterface
        {
            private Injector $injector;
            private ?Generator $iterator;
            private ?MiddlewareInterface $middleware = null;
            private ?RequestHandlerInterface $nextHandler = null;

            public function __construct(Generator $iterator, Injector $injector)
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
                if ($this->middleware === null) {
                    if ($this->iterator === null) {
                        return $this->nextHandler->handle($request);
                    }
                    if (!$this->iterator->valid()) {
                        /** @var RequestHandlerInterface $handler */
                        $this->nextHandler = $this->iterator->getReturn();
                        $this->iterator = null;
                        return $this->nextHandler->handle($request);
                    }
                    $this->middleware = $this->iterator->current();
                    $this->nextHandler = new self($this->iterator, $this->injector);
                    $this->iterator->next();
                    $this->iterator = null;
                }

                if ($this->middleware instanceof MiddlewareInterface) {
                    return $this->middleware->process($request, $this->nextHandler);
                }
                if (is_callable($this->middleware)) {
                    $result = $this->injector->invoke($this->middleware, [$request, $this->nextHandler]);
                    // Kostyl' for yiisoft/router
                    if ($result instanceof MiddlewareInterface) {
                        $this->middleware = $result;
                        return $result->process($request, $this->nextHandler);
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
    private function iteratePipelines(array $pipelines): Generator
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
            return $this->container->get($definition);
        }
        if (is_array($definition) && array_keys($definition) === [0, 1] && is_string($definition[0])) {
            return [$this->container->get($definition[0]), $definition[1]];
        }
        throw new InvalidMiddlewareDefinitionException(
            sprintf('Middleware MUST return instance of %s.', ResponseInterface::class)
        );
    }
}
