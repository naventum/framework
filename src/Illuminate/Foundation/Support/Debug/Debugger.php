<?php

namespace Naventum\Framework\Illuminate\Foundation\Support\Debug;

use Symfony\Component\ErrorHandler\Debug;
use Symfony\Component\ErrorHandler\ErrorHandler;

class Debugger
{
    public static function register()
    {
        $config = config('app');

        ErrorHandler::register();

        if ($config->env === 'development') {
            Debug::enable();
        }
    }
}
