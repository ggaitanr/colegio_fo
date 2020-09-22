<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAlumnosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('alumnos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('codigo',25)->unique();
            $table->string('primer_nombre',25);
            $table->string('segundo_nombre',25)->nullable();
            $table->string('tercer_nombre',25)->nullable();
            $table->string('primer_apellido',25);
            $table->string('segundo_apellido',25)->nullable();
            $table->datetime('fecha_nac');
            $table->char('genero',1);
            $table->string('telefono',25)->nullable();
            $table->string('email',25)->nullable();
            $table->string('direccion',50);
            $table->string('enfermedades',1000)->nullable();
            $table->string('alergias',1000)->nullable();
            $table->string('foto',50)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('alumnos');
    }
}
