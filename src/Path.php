<?php

namespace Naventum\Framework;

use Naventum\Framework\Illuminate\Support\Paths;

class Path
{
    private static $paths;

    public static function all()
    {
        if (static::$paths) {
            return static::$paths;
        }

        return static::generated(__DIR__);
    }

    private static function generated(string $base_path)
    {
        return static::$paths = Paths::generate($base_path, '/naventum');
    }
}
