<?php

declare(strict_types=1);

namespace roxblnfk\Contract\Router\ConfigB;

use Yiisoft\Http\Method;

class RouteFactory
{
    private function __construct()
    {}


    public static function methods(array|string $methods, string $pattern, callable|array|string $action): ConfigurableRoute
    {}

    public static function anyMethod(string $pattern, callable|array|string $action): ConfigurableRoute
    {
        return self::methods(Method::ALL, $pattern, $action);
    }

    public static function rest(string $pattern, callable|array|string $action): ConfigurableRoute
    {
        return self::methods([Method::GET, Method::POST, Method::PUT, Method::DELETE], $pattern, $action);
    }

    public static function web(string $pattern, callable|array|string $action): ConfigurableRoute
    {
        return self::methods([Method::GET, Method::POST], $pattern, $action);
    }

    public static function get(string $pattern, callable|array|string $action): ConfigurableRoute
    {
        return self::methods(Method::GET, $pattern, $action);
    }

    public static function post(string $pattern, callable|array|string $action): ConfigurableRoute
    {
        return self::methods(Method::POST, $pattern, $action);
    }

    public static function put(string $pattern, callable|array|string $action): ConfigurableRoute
    {
        return self::methods(Method::PUT, $pattern, $action);
    }

    public static function delete(string $pattern, callable|array|string $action): ConfigurableRoute
    {
        return self::methods(Method::DELETE, $pattern, $action);
    }
}
