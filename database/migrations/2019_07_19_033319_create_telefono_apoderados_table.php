<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTelefonoApoderadosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('telefono_apoderados', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->char('tipo_telefono',1);
            $table->string('telefono',15);
            $table->unsignedBigInteger('apoderado_id');

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
        Schema::dropIfExists('telefono_apoderados');
    }
}
