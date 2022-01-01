<?php

namespace Naventum\Framework\Illuminate\Support\Facades;

use Naventum\Framework\Illuminate\Foundation\Routing;

class Route extends Routing
{
    protected static $name;

    protected static $path;

    protected static $prefix;

    protected static $handle;

    protected static $routes;

    protected static $methods = [];

    protected static $middlewares = [];

    public static function get(string $path, $handle)
    {
        static::reset();
        static::$path = $path;
        static::$handle = $handle;
        static::$methods = ['GET', 'HEAD'];

        return new static;
    }

    public static function post(string $path, $handle)
    {
        static::reset();
        static::$path = $path;
        static::$handle = $handle;
        static::$methods = ['POST', 'HEAD'];

        return new static;
    }

    public static function middleware(array $middlewares)
    {
        static::$middlewares = $middlewares;

        return new static;
    }

    public static function prefix(string $prefix)
    {
        static::$prefix = $prefix;
        static::$path = static::$prefix . static::$path;

        return new static;
    }

    public static function name(string $name)
    {
        static::$name = $name;

        static::setRoute();
    }

    protected static function reset()
    {
        static::$name = null;
        static::$path = null;
        static::$prefix = '';
        static::$handle = [];
        static::$methods = [];
        static::$middlewares = [];
    }
}
