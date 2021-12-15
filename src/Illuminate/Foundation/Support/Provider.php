<?php

namespace Naventum\Framework\Illuminate\Foundation\Support;

class Provider
{
    public static function runAllBy(array $providers)
    {
        foreach ($providers as $provider) {
            static::run($provider);
        }

        return true;
    }

    protected static function run($provider)
    {
        $provider = new $provider;

        return $provider->boot();
    }
}
