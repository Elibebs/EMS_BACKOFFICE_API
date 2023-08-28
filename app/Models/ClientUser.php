<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Hyn\Tenancy\Traits\UsesSystemConnection;

class ClientUser extends Model
{
    use UsesSystemConnection;
	protected $primaryKey = "id";
    protected $table = "client.users";
    protected $dateFormat = 'Y-m-d H:i:sO';

}
