<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class IndexToComentarios extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void {
        Schema::table('evaluador_has_evaluado_comentarios', function (Blueprint $table) {
            // Búsqueda base por claves
            $table->index(
                ['campania_id','evaluado_id','campania_has_competencia_id'],
                'ehec_idx_camp_evaluado_comp'
            );
            // Para separar autoevaluación (4) vs otros y chequear NULL en evaluador_has_evaluado_id
            $table->index(['tipo_relacion_jerarquica_id'], 'ehec_idx_tipo_rel');
            $table->index(['evaluador_has_evaluado_id'], 'ehec_idx_ehe_id');
        });
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void {
        Schema::table('evaluador_has_evaluado_comentarios', function (Blueprint $table) {
            $table->dropIndex('ehec_idx_camp_evaluado_comp');
            $table->dropIndex('ehec_idx_tipo_rel');
            $table->dropIndex('ehec_idx_ehe_id');
        });
    }
}
