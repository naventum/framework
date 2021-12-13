<?php

namespace Naventum\Framework\Illuminate\Support;

class Paths
{
    public static $base_path;

    public static $public_path;

    public static $app_path;

    public static $config_path;

    public static $resource_path;

    public static $view_path;

    public static function initPaths(string $path)
    {
        static::setBasePath($path);

        $paths = static::generate(static::$base_path);

        static::$public_path = $paths['public_path'];
        static::$app_path = $paths['app_path'];
        static::$config_path = $paths['config_path'];
        static::$resource_path = $paths['resource_path'];
        static::$view_path = $paths['view_path'];
    }

    public static function setBasePath(string $path)
    {
        return static::$base_path = $path;
    }

    public static function generate(string $base_path, string $prefix = null)
    {
        return [
            'base_path' => $base_path,
            'public_path' => $base_path . $prefix . '/public',
            'app_path' => $base_path . $prefix . '/app',
            'config_path' => $base_path . $prefix . '/config',
            'resource_path' => $base_path . $prefix . '/resources',
            'view_path' => $base_path . $prefix . '/resources/views',
        ];
    }
}
