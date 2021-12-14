<?php

namespace Naventum\Framework\Illuminate\Foundation;

use Illuminate\Database\Capsule\Manager as Capsule;

class Database
{
    public static function register()
    {
        $capsule = static::capsule();
        $database = static::toArray(config('database'));

        static::addConnection($capsule, $database);

        $capsule->setAsGlobal();
        $capsule->bootEloquent();
    }

    private static function toArray(object $object)
    {
        return json_decode(json_encode($object), true);
    }

    private static function addConnection(object $capsule, array $database)
    {
        return $capsule->addConnection($database['connections'][$database['default']]);
    }

    private static function capsule()
    {
        return new Capsule;
    }
}
