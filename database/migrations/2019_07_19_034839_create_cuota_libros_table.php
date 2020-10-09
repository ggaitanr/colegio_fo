<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCuotaLibrosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cuota_libros', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('cuota_id');
            $table->unsignedBigInteger('libro_id');

            $table->foreign('cuota_id')->references('id')->on('cuotas');
            $table->foreign('libro_id')->references('id')->on('libros');
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
        Schema::dropIfExists('cuota_libros');
    }
}
