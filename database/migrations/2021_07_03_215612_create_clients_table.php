<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client.clients', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('uid');
            $table->string('name');
            $table->string('email', 191)->nullable()->index('users_email_idx');
            $table->string('phone_number', 191)->nullable();
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
        Schema::dropIfExists('client.clients');
    }
}
