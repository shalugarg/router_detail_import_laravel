<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRouterDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('router_details', function (Blueprint $table) {
            $table->increments('id');
            $table->string('Sapid')->length(18);
            $table->string('hostname',14);
            $table->string('loopback',20);
            $table->string('macaddress',20);
            $table->timestamps();
            $table->primary('id');
            $table->index(['Sapid', 'hostname','loopback','macaddress']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('router_details');
    }
}