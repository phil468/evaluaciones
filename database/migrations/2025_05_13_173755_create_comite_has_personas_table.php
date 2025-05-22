<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateComiteHasPersonasTable extends Migration
{
    public function up()
    {
        Schema::create('comite_has_personas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('comite_calibracion_id');
            $table->unsignedBigInteger('personal_id');
            $table->timestamps();

            // $table->foreign('comite_calibracion_id')->references('id')->on('comites_calibracion')->onDelete('cascade');
            // $table->foreign('personal_id')->references('id')->on('personals')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('comite_has_personas');
    }
}
