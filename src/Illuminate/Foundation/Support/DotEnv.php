<?php

namespace Naventum\Framework\Illuminate\Foundation\Support;

use Dotenv\Dotenv as DotenvByDotEnv;
use Dotenv\Repository\Adapter\EnvConstAdapter;
use Dotenv\Repository\Adapter\PutenvAdapter;
use Dotenv\Repository\RepositoryBuilder;

class DotEnv
{
    public static function register()
    {
        $repository = RepositoryBuilder::createWithNoAdapters()
            ->addAdapter(EnvConstAdapter::class)
            ->addWriter(PutenvAdapter::class)
            ->immutable()
            ->make();

        $dotenv = DotenvByDotEnv::create($repository, base_path());
        $dotenv->safeLoad();

        return true;
    }
}
