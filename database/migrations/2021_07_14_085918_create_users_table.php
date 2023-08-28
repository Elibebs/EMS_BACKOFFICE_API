<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client.users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('client_id');
            $table->string('name',191)->nullable();
            $table->string('email')->index('client_sers_email_idx');
            $table->string('password')->nullable();
            $table->string('status')->nullable();
            $table->string('access_token')->nullable();
            $table->string('session_id')->nullable();
            $table->datetimetz('session_id_time')->nullable();
			$table->datetimetz('last_logged_in')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('client.users');
    }
}
