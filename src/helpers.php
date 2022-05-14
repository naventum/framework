<?php

use Illuminate\Support\Carbon;
use Naventum\Framework\Illuminate\Foundation\Abort;
use Naventum\Framework\Illuminate\Support\Config;
use Naventum\Framework\Illuminate\Support\Paths;
use Naventum\Framework\Illuminate\Support\Request;
use Naventum\Framework\Illuminate\Support\Session;
use Naventum\Framework\Illuminate\Support\View;

if (!function_exists('view')) {
    function view(string $view, $data = [])
    {
        return View::make($view, null, $data);
    }
}

if (!function_exists('now')) {
    function now($tz = null)
    {
        return Carbon::now($tz);
    }
}

if (!function_exists('url')) {
    function url(string $path = null)
    {
        return config('app')->url . $path;
    }
}

if (!function_exists('assets')) {
    function assets(string $path = null)
    {
        return config('app')->asset_url . $path;
    }
}

if (!function_exists('toVariadic')) {
    function toVariadic(...$data)
    {
        return $data[0];
    }
}

if (!function_exists('redirect')) {
    function redirect($to, array $with = [])
    {
        foreach ($with as $key => $value) {
            $_SESSION['flash_data'][$key] = $value;
        }

        return header('location: '  . $to);
    }
}

if (!function_exists('session')) {
    function session()
    {
        return new Session;
    }
}

if (!function_exists('abort')) {
    function abort(int $code, string $message = null)
    {
        return (new Abort($code, $message))->response();
    }
}

if (!function_exists('request')) {
    function request()
    {
        return new Request;
    }
}

if (!function_exists('config')) {
    function config(string $filename)
    {
        return Config::make($filename, config_path('/'))->config();
    }
}

if (!function_exists('configArray')) {
    function configArray(string $filename)
    {
        return Config::make($filename, config_path('/'))->getArray();
    }
}

if (!function_exists('__')) {
    function __($text)
    {
        return htmlspecialchars($text);
    }
}

if (!function_exists('env')) {
    function env(string $name, $default = null)
    {
        return getenv($name) ?? $default;
    }
}

if (!function_exists('base_path')) {
    function base_path(string $path = null)
    {
        return Paths::$base_path . $path;
    }
}

if (!function_exists('public_path')) {
    function public_path(string $path = null)
    {
        return Paths::$public_path . $path;
    }
}

if (!function_exists('app_path')) {
    function app_path(string $path = null)
    {
        return Paths::$app_path . $path;
    }
}

if (!function_exists('config_path')) {
    function config_path(string $path = null)
    {
        return Paths::$config_path . $path;
    }
}

if (!function_exists('resource_path')) {
    function resource_path(string $path = null)
    {
        return Paths::$resource_path . $path;
    }
}

if (!function_exists('view_path')) {
    function view_path(string $path = null)
    {
        return Paths::$view_path . $path;
    }
}
