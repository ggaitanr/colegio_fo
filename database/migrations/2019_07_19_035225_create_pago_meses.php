<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePagoMeses extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pago_meses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('mes_id');
            $table->unsignedBigInteger('pago_id');
            
            $table->softDeletes();

            $table->foreign('mes_id')->references('id')->on('meses');
            $table->foreign('pago_id')->references('id')->on('pagos');
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
        Schema::dropIfExists('detalle_pagos_mes');
    }
}
