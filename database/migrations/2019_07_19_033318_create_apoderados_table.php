<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApoderadosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('apoderados', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('cui',15);
            $table->string('nit',15)->nullable();
            $table->unsignedBigInteger('municipio_id');
            $table->string('primer_nombre',25);
            $table->string('segundo_nombre',25)->nullable();
            $table->string('primer_apellido',25);
            $table->string('segundo_apellido',25)->nullable();
            $table->string('email')->nullable();
            $table->datetime('fecha_nac')->nullable();
            $table->string('ocupacion')->nullable();
            $table->string('direccion')->nullable();
            $table->string('nacionalidad',25);
            $table->string('estado_civil',25);
            $table->timestamps();

            $table->foreign('municipio_id')->references('id')->on('municipios');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('apoderados');
    }
}
