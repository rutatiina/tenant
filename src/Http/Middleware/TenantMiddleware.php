<?php

namespace Rutatiina\Tenant\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Rutatiina\Qbuks\Models\ServiceUser;
use Rutatiina\User\Models\UserDetails;

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

		if (optional($request->user()->details)->tenant_id)
		{
			session(['tenant_id' => $request->user()->details->tenant_id]);

			$db = $request->user()->tenant->database;
    		$db = (empty($db)) ? env('TENANT_DATABASE') : $db;

    		//change to tenant db
			config(['database.connections.tenant.database' => $db]);

			//Using purge() and reconnect() will ensure any query that runs in the future on the tenant connection will use the configuration from above.
			DB::purge('tenant');
			DB::reconnect('tenant');

    		return $next($request);
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

		// Log::info('session tenant_id has not been found ');

    	$request->user()->whereHas('services', function ($query) {
			$query->where('service_id', 1);
		})->first();

    	$service = $request->user()->services()->first();

    	//print_r($service->tenant_id); exit;

		if($service) {

			$service->load('tenant');
			$tenantId = $service->tenant->id;

			//var_dump($tenantId); exit;

			session(['tenant_id' => $tenantId]);

			// Log::info('user id is: '.$request->user()->id);
			// Log::info('tenant id is: '.$tenantId);

			//update the tenant id parameter of the user details
			UserDetails::updateOrCreate(
				['user_id' => $request->user()->id],
				['tenant_id' => $tenantId]
			);

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
