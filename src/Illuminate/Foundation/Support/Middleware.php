<?php

namespace Naventum\Framework\Illuminate\Foundation\Support;

class Middleware
{
    protected static $middlewares = [];

    public static function runAllBy(array $middlewares)
    {
        foreach ($middlewares as $middleware) {
            $response = static::run($middleware);

            if ($response == false) {
                return $response;
            }
        }

        return true;
    }

    public static function runAll()
    {
        foreach (static::$middlewares as $middleware) {
            static::run($middleware);
        }
    }

    protected static function run($middleware)
    {
        $middleware = new $middleware;

        $run = $middleware->handle();

        return $run;
    }
}
