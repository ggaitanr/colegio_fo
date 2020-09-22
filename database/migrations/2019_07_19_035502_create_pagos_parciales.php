<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePagosParciales extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pagos_parciales', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('pago_id');
            $table->unsignedBigInteger('apoderado_id');
            $table->decimal('pago',11,2);
            $table->datetime('fecha');
            $table->boolean('anulado')->default(0);
            $table->string('motivo_anulado',250)->nullable();
            
            $table->foreign('pago_id')->references('id')->on('pagos');
            $table->foreign('apoderado_id')->references('id')->on('apoderados');
            $table->softDeletes();


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
        Schema::dropIfExists('pagos_parciales');
    }
}
