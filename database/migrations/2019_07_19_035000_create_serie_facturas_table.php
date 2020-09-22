<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSerieFacturasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('serie_facturas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('serie',25);
            $table->integer('no_facturas');
            $table->integer('no_inicial')->default(1);
            $table->integer('no_actual')->nullable();
            $table->boolean('actual')->default(1);
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
        Schema::dropIfExists('serie_facturas');
    }
}
