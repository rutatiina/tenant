<?php

namespace Rutatiina\Tenant;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;

class TenantServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        include __DIR__.'/routes/routes.php';

        $this->loadViewsFrom(__DIR__.'/resources/views/limitless/', 'tenant');
        $this->loadMigrationsFrom(__DIR__.'/Database/Migrations');

    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->make('Rutatiina\Tenant\Http\Controllers\TenantController');

    }
}
