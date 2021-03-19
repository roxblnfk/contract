<?php

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface as Middleware;
use Psr\Http\Server\MiddlewareInterface as Middleware1;
use Psr\Http\Server\MiddlewareInterface as Middleware2;
use Psr\Http\Server\MiddlewareInterface as BlogController;
use Psr\Http\Server\MiddlewareInterface as AdminMiddleware;
use roxblnfk\Contract\Router\Factory\Route as R;
use Yiisoft\Http\Method;

return [
    R::get('/', 'home')
        ->pipe(Middleware::class)
        ->do(fn (ServerRequestInterface $request) => yield from $request->getQueryParams()),

    // Blog

    // Short group syntax
    R::group('/blog', [
        R::get('/index', 'blog::index')
            ->pipe(Middleware::class)
            ->do(fn (ServerRequestInterface $request) => yield from $request->getQueryParams()),
        R::web('/post/{id}', 'index') // GET & POST
            ->pipe(Middleware1::class)
            ->pipe(Middleware2::class, Method::POST) // POST only
            ->pipe(BlogController::class),

        R::web('/archive/[{month}[/{year}]]', 'archive') // GET & POST
            ->pipe(Middleware::class)
            ->pipe(BlogController::class),
    ]),

    // REST API

    // Alternative syntax with typed parameters and middlewares in group
    R::create('/api')
        ->pipe(Middleware1::class, [Method::PUT, Method::POST, Method::DELETE])
        ->pipe(Middleware2::class)
        ->group(
            R::get('schema')->do(['API\Crud', 'generate'], ['version' => 3]),

            // Admin panel
            R::create('/admin')
                ->pipe(AdminMiddleware::class)
                ->group(
                    // Blog module
                    R::create('blog')
                        ->pipe(Middleware::class, Method::GET)
                        ->group(
                            R::get('post/list', 'rest::blog::posts'),
                            R::rest('post/{id}', 'rest::blog::post')
                                ->do('RestApi\Admin\Blog\Post'), // callable with __invoke method
                            R::rest('post/best', 'rest::blog::bestPosts')->do('RestApi\Admin\Blog\bestPosts'),
                            R::create('post/{post}/comment/{id}')
                                ->name('rest::blog::comment'),
                                // you can't use the `group()` method after `name()` calling
                                // also actions and middlewares are optional
                            // ...
                            // ...
                            // ...
                        ),
                    // Admin notifications
                    R::methods([Method::GET, Method::DELETE], 'notifications')
                        ->do(['RestApi\Admin\Common', 'notifications'], ['format' => 'xml']),
                ),

            // ...
            R::group('/something', [
                // ...
                // ...
                // ...
            ]),
        ),
];

// How to read route parameters
// this is Route instance
$route = R::create('pattern')->name('fox')->do(fn () => false);
// Get name:
$route->getParameters()->getName();
