<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterPreguntasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('preguntas', function (Blueprint $table) {
            $table->boolean('estado')->default(true)->after('id');
            $table->unsignedBigInteger('campania_has_competencia_id')->nullable()->after('estado');
            $table->unsignedBigInteger('dominio_id')->nullable();
            $table->unsignedBigInteger('id')->autoIncrement()->change();

            $table->foreign('campania_has_competencia_id')->references('id')->on('campania_has_competencias');
            $table->foreign('dominio_id')->references('id')->on('dominios');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('preguntas', function (Blueprint $table) {
            $table->dropForeign(['campania_has_competencia_id']);
            $table->dropColumn('estado');
            $table->dropColumn('campania_has_competencia_id');
        });
    }
}