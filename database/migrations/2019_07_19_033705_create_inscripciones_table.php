<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInscripcionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inscripciones', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('numero')->unique();
            $table->string('documento',150)->nullable();
            $table->unsignedBigInteger('alumno_id');
            $table->unsignedBigInteger('grado_nivel_educativo_id');
            $table->unsignedBigInteger('ciclo_id');
            $table->char('jornada',1)->default('M');
            $table->datetime('fecha');
            $table->softDeletes();

            $table->foreign('alumno_id')->references('id')->on('alumnos');
            $table->foreign('grado_nivel_educativo_id')->references('id')->on('grados_niveles_educativos');
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
        Schema::dropIfExists('inscripciones');
    }
}
