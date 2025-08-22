<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterEvaluadorHasEvaluadoComentariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('evaluador_has_evaluado_comentarios', function (Blueprint $table) {
            // si existe FK a evaluador_has_evaluado_id, la quitamos
            if (Schema::hasColumn('evaluador_has_evaluado_comentarios', 'evaluador_has_evaluado_id')) {
                $table->dropIndex('ehe_sec_unique');
                $table->dropColumn('evaluador_has_evaluado_id');
            }

            if (!Schema::hasColumn('evaluador_has_evaluado_comentarios', 'evaluado_id')) {
                $table->unsignedBigInteger('evaluado_id')->after('id');
                $table->foreign('evaluado_id')->references('id')->on('personal')->onDelete('cascade');
            }
            if (!Schema::hasColumn('evaluador_has_evaluado_comentarios', 'campania_id')) {
                $table->unsignedBigInteger('campania_id')->after('evaluado_id');
                $table->foreign('campania_id')->references('id')->on('campanias')->onDelete('cascade');
            }
            if (!Schema::hasColumn('evaluador_has_evaluado_comentarios', 'tipo_relacion_jerarquica_id')) {
                $table->unsignedBigInteger('tipo_relacion_jerarquica_id')->nullable()->after('campania_id');
                $table->foreign('tipo_relacion_jerarquica_id')->references('id')->on('tipo_relacion_jerarquicas')->nullOnDelete();
            }

            // Aseguramos que exista la columna de competencia (por sección)
            if (!Schema::hasColumn('evaluador_has_evaluado_comentarios', 'campania_has_competencia_id')) {
                $table->unsignedBigInteger('campania_has_competencia_id')->nullable()->after('tipo_relacion_jerarquica_id');
                $table->foreign('campania_has_competencia_id')->references('id')->on('campania_has_competencias')->cascadeOnDelete();
            }

            // índice único para evitar duplicados por evaluado+campaña+competencia+tipo_relación
            // $table->unique(
            //     ['evaluado_id','campania_id','campania_has_competencia_id','tipo_relacion_jerarquica_id'],
            //     'ehec_unique_eval_camp_comp_tipo'
            // );
        });
    }

    public function down(): void
    {
        Schema::table('evaluador_has_evaluado_comentarios', function (Blueprint $table) {
            $table->dropUnique('ehec_unique_eval_camp_comp_tipo');

            if (Schema::hasColumn('evaluador_has_evaluado_comentarios','tipo_relacion_jerarquica_id')) {
                $table->dropForeign(['tipo_relacion_jerarquica_id']);
                $table->dropColumn('tipo_relacion_jerarquica_id');
            }
            if (Schema::hasColumn('evaluador_has_evaluado_comentarios','campania_id')) {
                $table->dropForeign(['campania_id']);
                $table->dropColumn('campania_id');
            }
            if (Schema::hasColumn('evaluador_has_evaluado_comentarios','evaluado_id')) {
                $table->dropForeign(['evaluado_id']);
                $table->dropColumn('evaluado_id');
            }
            // Nota: dejamos campania_has_competencia_id en caso de que ya existiera previamente
        });
    }
}
