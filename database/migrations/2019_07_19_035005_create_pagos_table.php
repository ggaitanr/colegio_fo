<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePagosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pagos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('inscripcion_id');
            $table->unsignedBigInteger('apoderado_id');
            $table->unsignedBigInteger('serie_factura_id');
            $table->boolean('is_credito')->default(false);
            $table->decimal('total',11,2);
            $table->decimal('total_cancelado',11,2)->nullable();
            $table->boolean('anulado')->default(0);
            $table->boolean('pagado')->default(0);
            $table->decimal('mora',10,2)->default(0);
            $table->integer('dias_mora')->default(0);
            $table->integer('exonerar_mora')->default(0);
            $table->string('motivo_anulado')->nullable();
            $table->string('descripcion',500)->nullable();
            $table->unsignedBigInteger('cuota_id');
            $table->datetime('fecha');

            $table->integer('factura');

            $table->foreign('inscripcion_id')->references('id')->on('inscripciones');
            $table->foreign('cuota_id')->references('id')->on('cuotas');
            $table->foreign('apoderado_id')->references('id')->on('apoderados');
            $table->foreign('serie_factura_id')->references('id')->on('serie_facturas');
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
        Schema::dropIfExists('pagos');
    }
}
