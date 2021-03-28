<?php

declare(strict_types=1);

namespace roxblnfk\Contract\Implementation\Router;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use roxblnfk\Contract\Pipeline\PipelineResolverInterface;
use roxblnfk\Contract\Router\RouterInterface;

final class RouterMiddleware implements MiddlewareInterface
{
    public function __construct(
        private RouterInterface $router,
        private PipelineResolverInterface $pipelineResolver,
    ) {
    }
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $result = $this->router->matchUri($request->getUri());
        $request = $request->withAttribute(self::class, $result);

        if ($result === null) {
            return $handler->handle($request);
        }

        return $this->pipelineResolver->resolvePipeline($result->getPipeline())($request);
    }
}
