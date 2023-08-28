<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Hyn\Tenancy\Traits\UsesSystemConnection;

class Logo extends Model
{
    use UsesSystemConnection;
	protected $primaryKey = "id";
    protected $table = "client.logos";
    protected $dateFormat = 'Y-m-d H:i:sO';

}
