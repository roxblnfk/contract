<?php
/**
 * @var $container ContainerInterface
 * @var $request ServerRequestInterface
 * @var $handler RequestHandlerInterface
 * @var $middleware1 MiddlewareInterface
 * @var $middleware2 MiddlewareInterface
 * @var $middleware3 MiddlewareInterface
 * @var $middleware4 MiddlewareInterface
 */

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use roxblnfk\Contract\Implementation\Pipeline\MiddlewaresPipelineResolver;
use roxblnfk\Contract\Pipeline\PipelineResolverInterface;

/** @var MiddlewaresPipelineResolver $dispatcher */
$dispatcher = $container->get(PipelineResolverInterface::class);

$handler = $dispatcher->withDefaultRequestHandler($handler)
    ->resolvePipeline(
        [
            $middleware1::class,
            $middleware2,
            fn() => $middleware3,
            [$middleware4, 'process'],
        ]
    );

$response = $handler($request);
