<?php

namespace Naventum\Framework\Illuminate\Foundation\Support;

class Middleware
{
    protected static $middlewares = [];

    public static function runAllBy(array $middlewares, $closure)
    {
        foreach ($middlewares as $middleware) {
            $response = static::run($middleware, $closure);

            if (!is_object($response)) {
                return $response;
            }
        }

        return $response();
    }

    public static function runAll($closure)
    {
        foreach (static::$middlewares as $middleware) {
            static::run($middleware, $closure);
        }
    }

    protected static function run($middleware, $closure)
    {
        $middleware = new $middleware;

        $next = function ($closure) {
            return $closure;
        };

        $response = $middleware->handle($next, $closure);

        return $response;
    }
}
