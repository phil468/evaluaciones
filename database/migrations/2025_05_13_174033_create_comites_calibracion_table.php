<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateComitesCalibracionTable extends Migration
{
    public function up()
    {
        Schema::create('comites_calibracion', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('personal_id');
            // $table->unsignedBigInteger('competencia_id');
            $table->unsignedBigInteger('campania_id');
            $table->string('comentario')->nullable();
            $table->string('area')->nullable();
            $table->string('nivel_jerarquico')->nullable();
            // $table->string('estado')->default('pendiente');
            $table->timestamps();

            // $table->foreign('personal_id')->references('id')->on('personals');
            // $table->foreign('competencia_id')->references('id')->on('secciones');
            // $table->foreign('campania_id')->references('id')->on('campanias');
        });
    }

    public function down()
    {
        Schema::dropIfExists('comites_calibracion');
    }
}
