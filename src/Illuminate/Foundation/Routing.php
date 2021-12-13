<?php

namespace Naventum\Framework\Illuminate\Foundation;

use Symfony\Component\Routing\Exception\MethodNotAllowedException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Route as SymfonyRoute;
use Symfony\Component\Routing\RouteCollection;

class Routing
{
    protected static $name;

    protected static $path;

    protected static $prefix;

    protected static $handle;

    protected static $methods = [];

    protected static $middlewares = [];

    protected static $modelBindings = [];

    protected static $classBindings = [];

    protected static $routes;

    public static function __startRouting()
    {
        static::$routes = new RouteCollection;
    }

    protected static function addRoutePath($path)
    {
        require_once $path;
    }

    protected static function setRoute()
    {
        $route = new SymfonyRoute(static::$path, [
            '_controller' => static::$handle[0],
            '_method' => static::$handle[1],
            '_middlewares' => static::$middlewares,
            '_modelBindings' => static::$modelBindings,
            '_classBindings' => static::$classBindings,
        ], [], [], '', [], static::$methods);
        static::$routes->add(static::$name, $route);

        return new static;
    }

    protected static function getRoute(string $path)
    {
        $context = new RequestContext('', $_SERVER['REQUEST_METHOD']);
        $matcher = new UrlMatcher(static::$routes, $context);

        try {
            return $matcher->match($path);
        } catch (\Throwable $th) {
            if ($th instanceof ResourceNotFoundException) {
                return abort(404);
            }

            if ($th instanceof MethodNotAllowedException) {
                return abort(405);
            }

            return abort(500);
        }
    }

    protected static function parseURI()
    {
        $url = filter_var($_SERVER['REQUEST_URI'], FILTER_SANITIZE_URL);

        return parse_url($url);
    }
}
