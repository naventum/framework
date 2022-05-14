<?php

namespace Naventum\Framework\Illuminate\Foundation\Cli;

class Stub
{
    private static $stub;
    
    private static $code;

    public static function make(string $string, array $ases)
    {
        self::$stub = $string;

        $parseData = self::parseData($ases);
        
        $keys = $parseData['keys'];
        $values = $parseData['values'];

        return self::makeCode($keys, $values);
    }

    public static function put(string $path)
    {
        file_put_contents(
            $path,
            self::$code
        );
    }

    private static function makeCode(array $keys, array $values)
    {
        self::$code = str_replace($keys, $values, self::$stub);

        return new self;
    }

    private static function parseData(array $ases)
    {
        $keys = [];
        $values = [];

        foreach ($ases as $key => $value) {
            $keys[] = $key;
            $values[] = $value;
        }

        return [
            'keys' => $keys,
            'values' => $values,
        ];
    }
}