<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Hyn\Tenancy\Traits\UsesSystemConnection;

class Client extends Model
{
    use UsesSystemConnection;
	protected $primaryKey = "id";
    protected $table = "client.clients";

}
