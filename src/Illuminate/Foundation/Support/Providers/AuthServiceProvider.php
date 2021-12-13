<?php

namespace Naventum\Framework\Illuminate\Foundation\Support\Providers;

use Naventum\Framework\Illuminate\Support\Facades\Auth as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->setUserFromDatabase();
    }
}
