<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApoderadoAlumnoTable extends Migration
{
    /**
    
     */
    public function up()
    {
        Schema::create('apoderados_alumnos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('apoderado_id');
            $table->unsignedBigInteger('alumno_id');
            $table->boolean('responsable')->default(0);
            $table->char('tipo_apoderado',1);
            

            $table->foreign('apoderado_id')->references('id')->on('apoderados');
            $table->foreign('alumno_id')->references('id')->on('alumnos');
            $table->timestamps();
        });
    }

    /**

     */
    public function down()
    {
        Schema::dropIfExists('apoderados_alumnos');
    }
}
