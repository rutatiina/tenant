<?php

namespace Rutatiina\Tenant;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;
use Rutatiina\Tenant\Http\Middleware\TenantMiddleware;

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

        //register the tenant middleware
        /** $this->app['router'] is an instance of Illuminate\Routing\Router */
        $this->app['router']->aliasMiddleware('tenant', TenantMiddleware::class);
        //$router->pushMiddlewareToGroup('web', TenantMiddleware::class);

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
