<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGradoSecNivEduTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('grado_sec_niv_edu', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('grado_nivel_educativo_id');
            $table->unsignedBigInteger('seccion_id');

            $table->foreign('grado_nivel_educativo_id')->references('id')->on('grados_niveles_educativos');
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
        Schema::dropIfExists('secciones_niveles_educativos');
    }
}
