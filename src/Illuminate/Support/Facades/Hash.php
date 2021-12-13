<?php

namespace Naventum\Framework\Illuminate\Support\Facades;

class Hash
{
    public static function make(string $password, $algo = PASSWORD_DEFAULT)
    {
        return password_hash($password, $algo);
    }

    public static function verify(string $password, string $hash)
    {
        return password_verify($password, $hash);
    }
}
