<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCamposToCampania extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('campanias', function (Blueprint $table) {
            // Agregar campos para la campaña actual
            //agregar un campo bioleano para identificar la campañ actual
            $table->boolean('es_campania_actual')->default(false)->after('estado')->comment('Indica si es la campaña actual');

            
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
        Schema::table('campania_actual', function (Blueprint $table) {
            // Eliminar el campo que identifica la campaña actual
            $table->dropColumn('es_campania_actual');
            //
        });
    }
}
