<?php

namespace Naventum\Framework\Illuminate\Foundation\Support;

use Dotenv\Dotenv;
use Dotenv\Repository\Adapter\EnvConstAdapter;
use Dotenv\Repository\Adapter\PutenvAdapter;
use Dotenv\Repository\RepositoryBuilder;
use Naventum\Framework\Illuminate\Support\Facades\App;
use Naventum\Framework\Illuminate\Support\Facades\Auth;
use Naventum\Framework\Illuminate\Support\Facades\Route;
use Naventum\Framework\Illuminate\Support\Paths;

class Init
{
    public function dotEnv()
    {
        $repository = RepositoryBuilder::createWithNoAdapters()
            ->addAdapter(EnvConstAdapter::class)
            ->addWriter(PutenvAdapter::class)
            ->immutable()
            ->make();

        $dotenv = Dotenv::create($repository, base_path());
        $dotenv->safeLoad();

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

    public function app(App $app)
    {
        return $app;
    }
}
