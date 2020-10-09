<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('inscripcion_id');
            $table->unsignedBigInteger('periodo_academico_id');
            $table->unsignedBigInteger('curso_id');
            $table->decimal('nota',4,2);

            $table->foreign('inscripcion_id')->references('id')->on('inscripciones');
            $table->foreign('periodo_academico_id')->references('id')->on('periodos_academicos');
            $table->foreign('curso_id')->references('id')->on('cursos');
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
        Schema::dropIfExists('notas');
    }
}
