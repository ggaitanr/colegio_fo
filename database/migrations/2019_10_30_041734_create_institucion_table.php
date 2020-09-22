<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInstitucionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('institucion', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('definicion');
            $table->string('nombre');
            $table->string('representante_legal')->nullable();
            $table->string('cui')->nullable();
            $table->string('logo')->nullable();
            $table->string('nit')->nullable();
            $table->integer('municipio_id')->nullable();
            $table->string('profesion')->nullable();
            $table->string('extendido_en')->nullable();
            $table->string('calidad_de')->nullable();
            $table->string('estado_civil')->nullable();
            $table->string('nacionalidad')->nullable();
            $table->datetime('fecha_nac')->nullable();
            $table->string('direccion',100)->nullable();
            $table->string('telefono',50)->nullable();
            $table->decimal('mora',5,2)->nullable();
            $table->integer('dia_pago')->nullable();
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
        Schema::dropIfExists('institucions');
    }
}
