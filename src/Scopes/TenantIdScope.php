<?php

namespace Rutatiina\Tenant\Scopes;

use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class TenantIdScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return void
     */
    public function apply(Builder $builder, Model $model)
    {
    	$in = [
    		'roles',
    		//'permissions', //to be deleted - permissions are system based i.e. are not tagged to any tenant

    		'rg_documents',

    		'rg_taxes',
    		'rg_accounting_payment_modes',
    		'rg_accounting_entrees',
    		'rg_accounting_txn_entree_configs',
    		'rg_accounting_documents',

    		'rg_banking_banks',

    		'rg_workshop_vehicles_makes',
    		'rg_workshop_vehicles_models',
		];

    	if (Auth::check())
    	{
    		//$app = Auth::user()->apps->first();
			$tenant_id = session('tenant_id'); //$app->tenant_id

			if (in_array($model->getTable(), $in))
			{
				$builder->whereIn($model->getTable().'.tenant_id', ['0', $tenant_id]);
			}
			else
            {
				$builder->where($model->getTable().'.tenant_id', $tenant_id);
			}
		}
    	elseif(config('app.scheduled_process'))
        {
    		//this is a scheduled process running
            //$builder->where('tenant_id', '>', 0);
            $builder->where($model->getTable().'.tenant_id', config('app.tenant_id'));
		}
    	else
        {
    		$builder->where($model->getTable().'.tenant_id', '0');
		}
    }
}
