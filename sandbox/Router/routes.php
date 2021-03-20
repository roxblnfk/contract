<?php

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface as Middleware;
use Psr\Http\Server\MiddlewareInterface as Middleware1;
use Psr\Http\Server\MiddlewareInterface as Middleware2;
use Psr\Http\Server\MiddlewareInterface as BlogController;
use Psr\Http\Server\MiddlewareInterface as ArchiveController;
use Psr\Http\Server\MiddlewareInterface as AdminMiddleware;
use roxblnfk\Contract\Router\ConfigA\RouteFactory as RA;
use roxblnfk\Contract\Router\ConfigB\RouteFactory as RB;
use roxblnfk\Contract\Router\ConfigB\GroupFactory as GB;
use Yiisoft\Http\Method;

$configA = [
    RA::get('/', 'home')
        ->pipe(Middleware::class)
        ->do(fn (ServerRequestInterface $request) => yield from $request->getQueryParams()),

    // Blog

    // Short group syntax
    RA::group('/blog', [
        RA::get('/index', 'blog::index')
            ->pipe(Middleware::class)
            ->do(fn (ServerRequestInterface $request) => yield from $request->getQueryParams()),
        RA::web('/post/{id}', 'index') // GET & POST
            ->pipe(Middleware1::class)
            ->pipe(Middleware2::class, Method::POST) // POST only
            ->pipe(BlogController::class),
        RA::web('/archive/[{month}[/{year}]]', 'archive') // GET & POST
            ->pipe(Middleware::class)
            ->pipe(ArchiveController::class),
    ]),

    // REST API

    // Alternative syntax with typed parameters and middlewares in group
    RA::create('/api')
        ->pipe(Middleware1::class, [Method::PUT, Method::POST, Method::DELETE])
        ->pipe(Middleware2::class)
        ->group(
            RA::get('schema')->do(['API\Crud', 'generate'], ['version' => 3]),

            // Admin panel
            RA::create('/admin')
                ->pipe(AdminMiddleware::class)
                ->group(
                    // Blog module
                    RA::create('blog')
                        ->pipe(Middleware::class, Method::GET)
                        ->group(
                            RA::get('post/list', 'rest::blog::posts'),
                            RA::rest('post/{id}', 'rest::blog::post')
                                ->do('RestApi\Admin\Blog\Post'), // callable with __invoke method
                            RA::rest('post/best', 'rest::blog::bestPosts')->do('RestApi\Admin\Blog\bestPosts'),
                            RA::create('post/{post}/comment/{id}')
                                ->name('rest::blog::comment'),
                                // you can't use the `group()` method after `name()` calling
                                // also actions and middlewares are optional
                            // ...
                            // ...
                            // ...
                        ),
                    // Admin notifications
                    RA::methods([Method::GET, Method::DELETE], 'notifications')
                        ->do(['RestApi\Admin\Common', 'notifications'], ['format' => 'xml']),
                ),

            // ...
            RA::group('/something', [
                // ...
                // ...
                // ...
            ]),
        ),
];

$configB = [

    RB::get('/', fn (ServerRequestInterface $request) => yield from $request->getQueryParams())
        ->addMiddleware(Middleware::class),

    // Blog

    GB::create('/blog', [
        RB::get('/index', fn (ServerRequestInterface $request) => yield from $request->getQueryParams())
            ->name('blog::index')
            ->addMiddleware(Middleware::class),
        RB::web('/post/{id}', BlogController::class) // GET & POST
            ->name('index')
            ->addMiddleware(Middleware1::class)
            ->addMiddleware(Middleware2::class),
        RB::web('/archive/[{month}[/{year}]]', ArchiveController::class) // GET & POST
            ->name('archive')
            ->addMiddleware(Middleware::class),
    ]),
];

// How to read route parameters
// this is RouteInterface instance
$route = RA::create('pattern')->name('fox')->do(fn () => false);
// Get name:
$route->getParameters()->getName();
