<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Hyn\Tenancy\Traits\UsesTenantConnection;

class Site extends Model
{
    use UsesTenantConnection;
	protected $primaryKey = "id";
    protected $table = "setup.sites";

    public function location()
    {
    	return $this->belongsTo('App\Models\Location', 'location_id');
    }

    public function sources()
    {
        return $this->hasMany('App\Models\SiteSource','site_id');
    }

}
