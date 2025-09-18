<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class IndexToResumenRespuestasCompetencias extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void {
        Schema::table('resumen_respuestas_evaluacion_desempeno_competencias', function (Blueprint $table) {
            // Llave compuesta única usada por updateOrCreate
            $table->unique(
                ['campania_id','personal_id','competencia_id','pregunta_id','area_id'],
                'resumen_unique_camp_persona_comp_preg_area'
            );

            // Lecturas típicas (dataTable): campaña + persona + competencia
            $table->index(
                ['campania_id','personal_id','competencia_id'],
                'resumen_idx_camp_persona_comp'
            );

            // Accesos por comité y por campaña
            $table->index(['comite_calibracion_id'], 'resumen_idx_comite');
            $table->index(['campania_id'], 'resumen_idx_campania');
        });
    }
    public function down(): void {
        Schema::table('resumen_respuestas_evaluacion_desempeno_competencias', function (Blueprint $table) {
            $table->dropUnique('resumen_unique_camp_persona_comp_preg_area');
            $table->dropIndex('resumen_idx_camp_persona_comp');
            $table->dropIndex('resumen_idx_comite');
            $table->dropIndex('resumen_idx_campania');
        });
    }
}
