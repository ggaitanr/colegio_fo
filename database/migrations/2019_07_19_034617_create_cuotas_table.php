<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCuotasTable extends Migration
{
    /**
    
     */
    public function up()
    {
        Schema::create('cuotas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->decimal('cuota',11,2);
            $table->unsignedBigInteger('grado_nivel_educativo_id');
            $table->unsignedBigInteger('concepto_pago_id');
            $table->unsignedBigInteger('ciclo_id');

            $table->foreign('grado_nivel_educativo_id')->references('id')->on('grados_niveles_educativos');
            $table->foreign('concepto_pago_id')->references('id')->on('concepto_pagos');
            $table->foreign('ciclo_id')->references('id')->on('ciclos');
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
        Schema::dropIfExists('cuotas');
    }
}
