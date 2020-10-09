<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGradosNivelesEducativosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('grados_niveles_educativos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('grado_id');
            $table->unsignedBigInteger('nivel_educativo_id');
            

            $table->foreign('grado_id')->references('id')->on('grados');
            $table->foreign('nivel_educativo_id')->references('id')->on('niveles_educativos');
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
        Schema::dropIfExists('grados_niveles_educativos');
    }
}
