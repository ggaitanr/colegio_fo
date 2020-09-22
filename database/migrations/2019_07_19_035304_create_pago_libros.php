<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePagoLibros extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pago_libros', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('cuota_libro_id');
            $table->unsignedBigInteger('pago_id');
            $table->unsignedBigInteger('apoderado_id');
            
            $table->foreign('cuota_libro_id')->references('id')->on('cuota_libros');
            $table->foreign('pago_id')->references('id')->on('pagos');
            $table->foreign('apoderado_id')->references('id')->on('apoderados');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('detalle_pagos_libro');
    }
}
