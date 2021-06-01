<?php

namespace Rutatiina\Tenant\Models;

use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
    protected $connection = 'system';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'rg_tenants';

    protected $primaryKey = 'id';

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

    public function getLogoAttribute($value)
    {
        return (file_exists(public_path('storage/'.$value))) ? $value : null;
    }

}
