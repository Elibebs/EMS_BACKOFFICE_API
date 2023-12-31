<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenancyHostnames extends Migration
{
    protected $system = true;

    public function up()
    {
        Schema::create('hostnames', function (Blueprint $table) {
            $table->bigIncrements('id',true);
            $table->string('fqdn')->unique();
            $table->string('redirect_to')->nullable();
            $table->boolean('force_https')->default(false);
            $table->timestamp('under_maintenance_since')->nullable();
            $table->bigInteger('website_id')->unsigned()->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('website_id')->references('id')->on('websites')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('hostnames');
    }
}
