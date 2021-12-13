<?php

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
        return Config::make($filename, '../config/')->config();
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
    function base_path()
    {
        return Paths::$base_path;
    }
}

if (!function_exists('public_path')) {
    function public_path()
    {
        return Paths::$public_path;
    }
}

if (!function_exists('app_path')) {
    function app_path()
    {
        return Paths::$app_path;
    }
}

if (!function_exists('config_path')) {
    function config_path()
    {
        return Paths::$config_path;
    }
}

if (!function_exists('resource_path')) {
    function resource_path()
    {
        return Paths::$resource_path;
    }
}

if (!function_exists('view_path')) {
    function view_path()
    {
        return Paths::$view_path;
    }
}
