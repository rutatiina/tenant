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

        $defaultMysqlDBConfig = config('database.connections.mysql', null);
        $defaultSystemDBConfig = config('database.connections.system', null);
        $defaultTenantDBConfig = config('database.connections.tenant', null);

        if ($defaultMysqlDBConfig && !$defaultSystemDBConfig)
        {
            $this->app['config']->set('database.connections.system', config('database.connections.mysql'));
        }

        if (!$defaultTenantDBConfig)
        {
            $this->app['config']->set('database.connections.tenant', [
                'driver' => 'mysql',
                'host' => env('TENANT_HOST', '127.0.0.1'),
                'port' => env('TENANT_PORT', '3306'),
                'database' => env('TENANT_DATABASE', 'forge'),
                'username' => env('TENANT_USERNAME', 'forge'),
                'password' => env('TENANT_PASSWORD', ''),
                'unix_socket' => env('TENANT_SOCKET', ''),
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
                'prefix' => '',
                'prefix_indexes' => true,
                'strict' => false,
                'engine' => null,
            ]);
        }

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
