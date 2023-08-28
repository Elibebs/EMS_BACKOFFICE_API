<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Hyn\Tenancy\Traits\UsesTenantConnection;

class Location extends Model
{
    use UsesTenantConnection;
	protected $primaryKey = "id";
    protected $table = "setup.locations";

    public function sites()
    {
        return $this->hasMany('App\Models\Site','location_id');
    }

}
