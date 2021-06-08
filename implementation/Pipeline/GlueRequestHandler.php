<?php

declare(strict_types=1);

namespace roxblnfk\Contract\Implementation\Pipeline;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use roxblnfk\Contract\Implementation\Pipeline\Exception\InvalidMiddlewareDefinitionException;

final class GlueRequestHandler implements RequestHandlerInterface
{
    private \Generator $iterator;

    public function __construct(\Generator $iterator)
    {
        $this->iterator = $iterator;
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        return $this->handle($request);
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        if (!$this->iterator->valid()) {
            /** @var RequestHandlerInterface $handler */
            $handler = $this->iterator->getReturn();
            return $handler->handle($request);
        }
        $middleware = $this->iterator->current();
        $this->iterator->next();
        $nextHandler = new self($this->iterator);

        if ($middleware instanceof MiddlewareInterface) {
            return $middleware->process($request, $nextHandler);
        }
        if (is_callable($middleware)) {
            $result = $middleware($request, $nextHandler);
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
}
