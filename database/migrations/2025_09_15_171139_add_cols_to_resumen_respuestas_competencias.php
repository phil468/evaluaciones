<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColsToResumenRespuestasCompetencias extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('resumen_respuestas_evaluacion_desempeno_competencias', function (Blueprint $table) {
            $table->decimal('total_peso', 10, 4)->nullable()->after('puntaje');
            $table->decimal('puntaje_autoevaluacion', 10, 4)->nullable()->after('total_peso');
            //
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('resumen_respuestas_evaluacion_desempeno_competencias', function (Blueprint $table) {
            $table->dropColumn(['total_peso', 'puntaje_autoevaluacion']);
            //
        });
    }
}
