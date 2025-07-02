<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDominioHasPreguntaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dominio_has_pregunta', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('dominio_id');
            $table->unsignedBigInteger('pregunta_id');
            $table->integer('numero_orden')->default(1);
            $table->boolean('estado')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('dominio_id')->references('id')->on('dominios');
            $table->foreign('pregunta_id')->references('id')->on('preguntas');

            $table->unique(['dominio_id', 'pregunta_id', 'numero_orden'], 'dominio_pregunta_numero_orden_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dominio_has_pregunta');
    }
}