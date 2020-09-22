<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConceptoPagosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('concepto_pagos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nombre',25);
            $table->boolean('is_parcial')->default(1);
            $table->boolean('obligatorio')->default(1);
            $table->boolean('mora')->default(0);
            $table->char('forma_pago',1)->default('N');
            $table->softDeletes();
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
        Schema::dropIfExists('concepto_pagos');
    }
}
