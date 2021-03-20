<?php

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface as Middleware;
use Psr\Http\Server\MiddlewareInterface as Middleware1;
use Psr\Http\Server\MiddlewareInterface as Middleware2;
use Psr\Http\Server\MiddlewareInterface as Middleware3;
use Psr\Http\Server\MiddlewareInterface as BlogController;
use Psr\Http\Server\MiddlewareInterface as ArchiveController;
use Psr\Http\Server\MiddlewareInterface as AdminMiddleware;
use roxblnfk\Contract\Router\ConfigB\RouteFactory as Route;
use roxblnfk\Contract\Router\ConfigB\GroupFactory as Group;
use Yiisoft\Http\Method;

return [
    Route::get('/', fn (ServerRequestInterface $request) => yield from $request->getQueryParams())
        ->name('home')
        ->addMiddleware(Middleware::class),

    // Blog


    Group::create('/blog', [
        Route::get('/index', fn (ServerRequestInterface $request) => yield from $request->getQueryParams())
            ->name('blog::index')
            ->addMiddleware(Middleware::class),
        Route::web('/post/{id}', BlogController::class) // GET & POST
            ->name('index')
            ->addMiddleware(Middleware3::class)
            ->addMiddleware(Middleware2::class, Method::POST) // POST only)
            ->addMiddleware(Middleware1::class),
        Route::web('/archive/[{month}[/{year}]]', ArchiveController::class) // GET & POST
            ->name('archive')
            ->addMiddleware(Middleware::class),
    ]),

    // REST API


    Group::create('/api', [
        Route::get('/schema', ['API\Crud', 'generate']),

        // Admin panel
        Group::create('/admin', [
            // Blog module
            Group::create('blog', [
                Route::get('post/list')->name('rest::blog::posts'), // actions and middlewares are optional
                Route::rest('post/{id}', 'RestApi\Admin\Blog\Post') // Action is callable with __invoke method
                    ->name('rest::blog::post'),
                Route::rest('post/best', 'RestApi\Admin\Blog\bestPosts')->name('rest::blog::bestPosts'),
                Route::anyMethod('post/{post}/comment/{id}')
                    ->name('rest::blog::comment')
                    ->override(),
                // ...
                // ...
                // ...
            ])
                ->addMiddleware(Middleware::class, Method::GET),
            // Admin notifications
            Route::methods([Method::GET, Method::DELETE], '/notifications', ['RestApi\Admin\Common', 'notifications'])
                ->name('rest::notifications'),
        ])
            ->addMiddleware(AdminMiddleware::class),

        // ...
        Group::create('/something', [
            // ...
            // ...
            // ...
        ]),
    ])
        ->addMiddleware(Middleware2::class)
        ->addMiddleware(Middleware1::class, [Method::PUT, Method::POST, Method::DELETE])
];

// How to read route parameters
// this is RouteInterface instance
$route = Route::anyMethod('pattern', fn () => false)->name('fox');
// Get name:
$route->getParameters()->getName();
