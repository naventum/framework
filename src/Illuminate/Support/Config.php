<?php

namespace Naventum\Framework\Illuminate\Support;

use Illuminate\Support\Str;

class Config
{
    private static $path;

    private static $config;

    private static $currentKey;

    private static $filename;

    private static $filenameWithExtension;

    private static $fullPath;

    public static function make(string $filename, string $path = null)
    {
        static::$filename = $filename;
        static::$filenameWithExtension = $filename . '.php';
        static::$path = $path;
        static::$fullPath = static::$path . static::$filenameWithExtension;

        static::init();

        return new static;
    }

    private static function init()
    {
        static::setPath();

        if (isset(static::$config[static::$currentKey])) {
            return static::$config[static::$currentKey];
        }

        return static::setConfig();
    }

    private static function setConfig()
    {
        $config = require static::$fullPath;

        return static::$config[static::$currentKey] = $config;
    }

    private static function setKey()
    {
        return static::$currentKey = static::makeKey(static::$filename);
    }

    private static function makeKey(string $string)
    {
        return Str::slug($string);
    }

    private static function setPath()
    {
        static::setKey();

        if (file_exists('../config/' . static::$filenameWithExtension)) {
            static::$fullPath = '../config/' . static::$filenameWithExtension;

            return static::setKey();
        }
    }

    public function getArray()
    {
        return static::$config[static::$currentKey];
    }

    public function config()
    {
        return (new ToObject(static::$config[static::$currentKey]))->object();
    }
}
