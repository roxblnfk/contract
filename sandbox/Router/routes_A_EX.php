<?php

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface as Middleware;
use Psr\Http\Server\MiddlewareInterface as Middleware1;
use Psr\Http\Server\MiddlewareInterface as Middleware2;
use Psr\Http\Server\MiddlewareInterface as Middleware3;
use Psr\Http\Server\MiddlewareInterface as BlogController;
use Psr\Http\Server\MiddlewareInterface as ArchiveController;
use Psr\Http\Server\MiddlewareInterface as AdminMiddleware;
use roxblnfk\Contract\Router\ConfigA\RouteFactory as Route;
use Yiisoft\Http\Method;


return [
    Route::get('/', 'home')
        ->pipe(Middleware::class)
        ->do(fn (ServerRequestInterface $request) => yield from $request->getQueryParams()),

    // Blog

    // Short group syntax
    Route::group('/blog', [
        Route::get('/index', 'blog::index')
            ->pipe(Middleware::class)
            ->do(fn (ServerRequestInterface $request) => yield from $request->getQueryParams()),
        Route::web('/post/{id}', 'index') // GET & POST
            ->pipe(Middleware1::class)
            ->pipe(Middleware2::class, Method::POST) // POST only
            ->pipe(Middleware3::class)
            ->pipe(BlogController::class),
        Route::web('/archive/[{month}[/{year}]]', 'archive') // GET & POST
            ->pipe(Middleware::class)
            ->pipe(ArchiveController::class),
    ]),

    // REST API

    // Alternative syntax with typed parameters and middlewares in group
    Route::groupEx('/api', [
        Middleware1::class,
        Middleware2::class
    ], [
        Route::get('/schema')->do(['API\Crud', 'generate'], ['version' => 3]),

        // Admin panel
        Route::groupEx('/admin', [
            AdminMiddleware::class,
        ], [
            // Blog module
            Route::groupEx('blog', [
                Middleware::class
            ], [
                Route::get('post/list', 'rest::blog::posts'), // actions and middlewares are optional
                Route::rest('post/{id}', 'rest::blog::post')
                    ->do('RestApi\Admin\Blog\Post'), // callable with __invoke method
                Route::rest('post/best', 'rest::blog::bestPosts')->do('RestApi\Admin\Blog\bestPosts'),
                Route::create('post/{post}/comment/{id}')
                    ->name('rest::blog::comment') // you can't use the `group()` method after `name()`
                    ->override(),
                // ...
                // ...
                // ...
            ]),
            // Admin notifications
            Route::methods([Method::GET, Method::DELETE], '/notifications', 'rest::notifications')
                ->do(['RestApi\Admin\Common', 'notifications'], ['format' => 'xml']),
        ]),

        // ...
        Route::group('/something', [
            // ...
            // ...
            // ...
        ]),
    ]),
];

// How to read route parameters
// this is RouteInterface instance
$route = Route::create('pattern')->name('fox')->do(fn () => false);
// Get name:
$route->getParameters()->getName();
