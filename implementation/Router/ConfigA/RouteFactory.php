<?php

declare(strict_types=1);

namespace roxblnfk\Contract\Implementation\Router\ConfigA;

use Psr\Http\Server\MiddlewareInterface;
use roxblnfk\Contract\Router\Route\RouteInterface;
use Yiisoft\Http\Method;

class RouteFactory
{
    /**
     * Begin declaration a group or a route with all HTTP methods
     */
    public static function create(string $pattern): AbstractRoute
    {
        return new AbstractRoute($pattern);
    }

    public static function methods(array|string $methods, string $pattern, string $name = null): ConfigurableRoute
    {
        return (new ConfigurableRoute($pattern, $name))->methods((array)$methods);
    }

    public static function allMethods(string $pattern, string $name = null): ConfigurableRoute
    {
        return self::methods(Method::ALL, $pattern, $name);
    }

    public static function rest(string $pattern, string $name = null): ConfigurableRoute
    {
        return self::methods([Method::GET, Method::POST, Method::PUT, Method::DELETE], $pattern, $name);
    }

    public static function web(string $pattern, string $name = null): ConfigurableRoute
    {
        return self::methods([Method::GET, Method::POST], $pattern, $name);
    }

    public static function get(string $pattern, string $name = null): ConfigurableRoute
    {
        return self::methods(Method::GET, $pattern, $name);
    }

    public static function post(string $pattern, string $name = null): ConfigurableRoute
    {
        return self::methods(Method::POST, $pattern, $name);
    }

    public static function put(string $pattern, string $name = null): ConfigurableRoute
    {
        return self::methods(Method::PUT, $pattern, $name);
    }

    public static function delete(string $pattern, string $name = null): ConfigurableRoute
    {
        return self::methods(Method::DELETE, $pattern, $name);
    }

    /**
     * @param RouteFactory[] $routes
     */
    public static function group(string $pattern, array $routes): RouteInterface
    {
        return self::create($pattern)->group(... $routes);
    }

    /**
     * @param array<int, MiddlewareInterface|callable|array|string> $middlewares
     * @param RouteFactory[] $routes
     */
    public static function groupEx(string $pattern, array $middlewares, array $routes): RouteInterface
    {
        $route = self::create($pattern);
        foreach ($middlewares as $middleware) {
            $route->pipe($middleware);
        }
        return $route->group(... $routes);
    }
}
