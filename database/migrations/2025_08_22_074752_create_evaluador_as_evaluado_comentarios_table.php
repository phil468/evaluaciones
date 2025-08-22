<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEvaluadorAsEvaluadoComentariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('evaluador_has_evaluado_comentarios', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('evaluador_has_evaluado_id');      // vínculo a la evaluación actual
            $table->unsignedBigInteger('campania_has_competencia_id');    // sección/competencia
            $table->text('comentario');
            $table->timestamps();

            $table->unique(['evaluador_has_evaluado_id', 'campania_has_competencia_id'], 'ehe_sec_unique');

            $table->foreign('evaluador_has_evaluado_id')->references('id')->on('evaluador_has_evaluados')->onDelete('cascade')->name('fk_1');
            $table->foreign('campania_has_competencia_id')->references('id')->on('campania_has_competencias')->onDelete('cascade')->name('fk_2');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('evaluador_has_evaluado_comentarios');
    }
}
