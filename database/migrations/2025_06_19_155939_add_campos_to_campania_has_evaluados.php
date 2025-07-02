<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCamposToCampaniaHasEvaluados extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('campania_has_evaluados', function (Blueprint $table) {
            // Campos de habilitación para evaluaciones 'puntaje_de_evaluacion_de_competencias', 'evaluacion_de_competencias_completada',
            $table->decimal('puntaje_de_evaluacion_de_competencias', 5, 2)->nullable()->after('habilitado_para_evaluacion_por_objetivos')->comment('Puntaje de evaluación de competencias');
            $table->boolean('evaluacion_de_competencias_completada')->default(false)->after('puntaje_de_evaluacion_de_competencias')->comment('Indica si la evaluación de competencias está completada');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('campania_has_evaluados', function (Blueprint $table) {
            // Eliminar los campos añadidos
            $table->dropColumn('puntaje_de_evaluacion_de_competencias');
            $table->dropColumn('evaluacion_de_competencias_completada');
            //
        });
    }
}
