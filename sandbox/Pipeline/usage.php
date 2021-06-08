<?php
/**
 * @var $container \Psr\Container\ContainerInterface
 * @var $request \Psr\Http\Message\ServerRequestInterface
 * @var $middleware1 \Psr\Http\Server\MiddlewareInterface
 * @var $middleware2 \Psr\Http\Server\MiddlewareInterface
 * @var $middleware3 \Psr\Http\Server\MiddlewareInterface
 * @var $middleware4 \Psr\Http\Server\MiddlewareInterface
 */

use roxblnfk\Contract\Implementation\Pipeline\MiddlewaresPipelineResolver;
use roxblnfk\Contract\Pipeline\PipelineResolverInterface;

/** @var MiddlewaresPipelineResolver $dispatcher */
$dispatcher = $container->get(PipelineResolverInterface::class);

$handler = $dispatcher->resolvePipeline([
    $middleware1::class,
    $middleware2,
    fn () => $middleware3,
    [$middleware4, 'process'],
]);

$response = $handler($request);
