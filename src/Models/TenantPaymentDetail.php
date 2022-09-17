<?php

namespace Rutatiina\Tenant\Models;

use Illuminate\Database\Eloquent\Model;
use Rutatiina\Tenant\Scopes\TenantIdScope;

class TenantPaymentDetail extends Model
{
    protected $connection = 'system';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'rg_tenant_payment_details';

    protected $primaryKey = 'id';

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new TenantIdScope);
    }

    public function rgGetAttributes()
    {
        $attributes = [];
        $describeTable =  \DB::connection('system')->select('describe ' . $this->getTable());

        foreach ($describeTable  as $row) {

            if (in_array($row->Field, ['id', 'created_at', 'updated_at', 'deleted_at', 'tenant_id', 'user_id'])) continue;

            if (in_array($row->Field, ['currencies', 'taxes'])) {
                $attributes[$row->Field] = [];
                continue;
            }

            if ($row->Default == '[]') {
                $attributes[$row->Field] = [];
            } else {
                $attributes[$row->Field] = $row->Default;
            }
        }

        //add the relationships
        //$attributes['comments'] = [];

        return $attributes;
    }

}
