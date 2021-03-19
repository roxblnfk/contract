<?php

declare(strict_types=1);

namespace roxblnfk\Contract\Router;

use roxblnfk\Contract\Router\Route\AbstractRoute;
use roxblnfk\Contract\Router\Route\ConfigurableRoute;
use roxblnfk\Contract\Router\Route\RouteGroup;
use Yiisoft\Http\Method;

/**
 * Factory
 */
class Route
{
    private function __construct()
    {}

    /**
     * Declare a route with all methods or group
     */
    public static function create(string $path): AbstractRoute
    {}

    public static function methods(array|string $methods, string $path, string $name = null): ConfigurableRoute
    {}

    public static function allMethods(string $path, string $name = null): ConfigurableRoute
    {
        return self::methods(Method::ALL, $path, $name);
    }

    public static function rest(string $path, string $name = null): ConfigurableRoute
    {
        return self::methods([Method::GET, Method::POST, Method::PUT, Method::DELETE], $path, $name);
    }

    public static function web(string $path, string $name = null): ConfigurableRoute
    {
        return self::methods([Method::GET, Method::POST], $path, $name);
    }

    public static function get(string $path, string $name = null): ConfigurableRoute
    {
        return self::methods(Method::GET, $path, $name);
    }

    public static function post(string $path, string $name = null): ConfigurableRoute
    {
        return self::methods(Method::POST, $path, $name);
    }

    public static function put(string $path, string $name = null): ConfigurableRoute
    {
        return self::methods(Method::PUT, $path, $name);
    }

    public static function delete(string $path, string $name = null): ConfigurableRoute
    {
        return self::methods(Method::DELETE, $path, $name);
    }

    /**
     * @param Route[] $routes
     */
    public static function group(string $path, array $routes): RouteGroup
    {
        return self::create($path)->group(... $routes);
    }
}
