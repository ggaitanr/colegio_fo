<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAsignacionSeccionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('asignacion_seccions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('inscripcion_id')->unique();
            $table->unsignedBigInteger('seccion_id');

            $table->foreign('inscripcion_id')->references('id')->on('inscripciones')->onDelete('cascade');
            $table->foreign('seccion_id')->references('id')->on('secciones')->onDelete('cascade');

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
        Schema::dropIfExists('asignacion_seccions');
    }
}
