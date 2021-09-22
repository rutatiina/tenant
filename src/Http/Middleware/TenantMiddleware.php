<?php

namespace Rutatiina\Tenant\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Rutatiina\Admin\Models\ServiceUser;

class TenantMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
    	if(!Auth::check()) {
            return redirect(route('login'));
        }

    	if (session('tenant_id')) {

    		$db = $request->user()->tenant->database;
    		$db = (empty($db)) ? env('TENANT_DATABASE') : $db;

    		//Log::info(DB::getDatabaseName());
    		//Log::info('tenant_id from session: '.$db);

    		//change to tenant db
			config(['database.connections.tenant.database' => $db]);

			//Using purge() and reconnect() will ensure any query that runs in the future on the tenant connection will use the configuration from above.
			DB::purge('tenant');
			DB::reconnect('tenant');

    		return $next($request);
		}

    	$request->user()->whereHas('services', function ($query) {
			$query->where('service_id', 1);
		})->first();

    	$service = $request->user()->services()->first();

    	//print_r($service->tenant_id); exit;

		if($service) {

			$service->load('tenant');

			//var_dump($service->tenant->id); exit;

			session(['tenant_id' => $service->tenant->id]);

			$db = $service->tenant->database;
    		$db = (empty($db)) ? env('TENANT_DATABASE') : $db;

    		//Log::info('tenant_id from service: '.$db);

    		//change to tenant db
			config(['database.connections.tenant.database' => $db]);

			//Using purge() and reconnect() will ensure any query that runs in the future on the tenant connection will use the configuration from above.
			DB::purge('tenant');
			DB::reconnect('tenant');

		} else {
			session(['tenant_id' => 0]);

			return redirect(route('organisations.create'));
		}

        return $next($request);
    }
}
