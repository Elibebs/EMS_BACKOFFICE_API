<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenancyWebsites extends Migration
{

    public function up()
    {
        Schema::create('websites', function (Blueprint $table) {
            $table->bigIncrements('id',true);
            $table->string('uuid',191);
            $table->timestamps();
            $table->softDeletes();
            $table->string('managed_by_database_connection', 191)->nullable()->comment('References the database connection key in your database.php');
			$table->string('name', 64)->nullable();
			$table->string('code', 64)->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('websites');
    }
}
