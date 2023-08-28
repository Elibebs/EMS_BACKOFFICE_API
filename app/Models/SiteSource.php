<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Hyn\Tenancy\Traits\UsesTenantConnection;

class SiteSource extends Model
{
    use UsesTenantConnection;
	protected $primaryKey = "id";
    protected $table = "setup.site_sources";

    public $timestamps = false;

    public function site()
    {
    	return $this->belongsTo('App\Models\Site', 'site_id');
    }

}
