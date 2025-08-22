<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelacionJerarquicaIdEvaluadorHasEvaluadoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('evaluador_has_evaluados', function (Blueprint $table) {
            if (!Schema::hasColumn('evaluador_has_evaluados', 'relacion_jerarquica_id')) {
                $table->unsignedBigInteger('relacion_jerarquica_id')->nullable()->after('peso_prorrateado');
                $table->foreign('relacion_jerarquica_id')->references('id')->on('tipo_relacion_jerarquicas')->nullOnDelete();
            }
        });
        //
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('evaluador_has_evaluados', function (Blueprint $table) {
            if (Schema::hasColumn('evaluador_has_evaluados', 'relacion_jerarquica_id')) {
                $table->dropForeign(['relacion_jerarquica_id']);
                $table->dropColumn('relacion_jerarquica_id');
            }
        });
        //
    }
}
