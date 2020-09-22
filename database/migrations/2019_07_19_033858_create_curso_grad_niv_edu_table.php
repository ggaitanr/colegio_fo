<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCursoGradNivEduTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('curso_grad_niv_edu', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('curso_id');
            $table->unsignedBigInteger('grado_nivel_educativo_id');
            $table->softDeletes();

            $table->foreign('curso_id')->references('id')->on('cursos');
            $table->foreign('grado_nivel_educativo_id')->references('id')->on('grados_niveles_educativos');
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
        Schema::dropIfExists('cursos_grados_niveles_educativos');
    }
}
