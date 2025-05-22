<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResumenRespuestasEvaluacionDesempenoCompetenciasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('resumen_respuestas_evaluacion_desempeno_competencias', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('personal_id');
            $table->unsignedBigInteger('competencia_id');
            $table->unsignedBigInteger('pregunta_id');
            $table->float('puntaje')->nullable();
            $table->float('puntaje_calibrado')->nullable();
            $table->unsignedBigInteger('area_id')->nullable();
            $table->unsignedBigInteger('campania_id');
            $table->unsignedBigInteger('comite_calibracion_id')->nullable();            
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('resumen_respuestas_evaluacion_desempeno_competencias');
    }
}
