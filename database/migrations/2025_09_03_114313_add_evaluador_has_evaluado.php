<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEvaluadorHasEvaluado extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('evaluador_has_evaluado_comentarios', function (Blueprint $table) {
            $table->foreignId('evaluador_has_evaluado_id')
                ->nullable()
                ->after('id')
                ->constrained('evaluador_has_evaluados')
                ->name('fk_evaluador_has_evaluado_id')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('evaluador_has_evaluado_comentarios', function (Blueprint $table) {
            //
        });
    }
}
