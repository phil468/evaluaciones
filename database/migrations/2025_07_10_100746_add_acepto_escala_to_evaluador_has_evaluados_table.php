<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAceptoEscalaToEvaluadorHasEvaluadosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('evaluador_has_evaluados', function (Blueprint $table) {
            $table->boolean('acepto_escala')->nullable()->default(false)->after('realizado');
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
        Schema::table('evaluador_has_evaluados', function (Blueprint $table) {
            $table->dropColumn('acepto_escala');
            //
        });
    }
}
