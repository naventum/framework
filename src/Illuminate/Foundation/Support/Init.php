<?php

namespace Naventum\Framework\Illuminate\Foundation\Support;

use Naventum\Framework\Illuminate\Foundation\Database;
use Naventum\Framework\Illuminate\Support\Facades\App;
use Naventum\Framework\Illuminate\Support\Facades\Auth;
use Naventum\Framework\Illuminate\Support\Facades\Route;
use Naventum\Framework\Illuminate\Support\Paths;

class Init
{
    public function dotEnv()
    {
        DotEnv::register();

        return $this;
    }

    public function paths(string $basepath)
    {
        Paths::initPaths($basepath);

        return $this;
    }

    public function routing()
    {
        Route::__startRouting();

        return $this;
    }

    public function session()
    {
        Auth::start();

        return $this;
    }

    public function db()
    {
        Database::register();

        return $this;
    }

    public function app(App $app)
    {
        return $app;
    }
}
