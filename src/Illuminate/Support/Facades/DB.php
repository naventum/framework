<?php

namespace Naventum\Framework\Illuminate\Support\Facades;

use Illuminate\Database\Capsule\Manager;
use Naventum\Framework\Illuminate\Database\Connection;

class DB extends Manager
{
    public static function run()
    {
        static::conn();

        return new static;
    }

    private static function conn()
    {
        return (new Connection)->___conn();
    }
}
