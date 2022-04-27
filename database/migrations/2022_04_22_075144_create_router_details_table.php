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
            $table->bigIncrements('id');
            $table->char('sapid')->length(18);
            $table->char('hostname',14);
            $table->char('loopback',15);
            $table->char('macaddress',17);
            $table->timestamps();
            $table->primary('id');
            $table->index(['sapid', 'hostname']);
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