<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableEvaluadorHasEvaluadoRespuestasTmp extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('evaluador_has_evaluado_respuestas_tmp', function (Blueprint $table) {
            $table->id();
            $table->foreignId('evaluador_has_evaluado_id');
            $table->foreignId('pregunta_id');
            $table->unsignedTinyInteger('valor_numerico'); // 1..10
            $table->timestamps();

            $table->unique(['evaluador_has_evaluado_id','pregunta_id'], 'uniq_tmp_respuesta_por_pregunta');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('table_evaluador_has_evaluado_respuestas_tmp');
    }
}
