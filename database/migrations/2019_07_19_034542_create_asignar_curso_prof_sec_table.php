<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAsignarCursoProfSecTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('asignar_curso_prof_sec', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('asignar_curso_profersor_id');
            $table->unsignedBigInteger('seccion_id');

            $table->foreign('asignar_curso_profersor_id')->references('id')->on('asignar_curso_profesor');
            $table->foreign('seccion_id')->references('id')->on('secciones');
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
        Schema::dropIfExists('cursos_grados_niveles_educativos_profesores_secciones');
    }
}
