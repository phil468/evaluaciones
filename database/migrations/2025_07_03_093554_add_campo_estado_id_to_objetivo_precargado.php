<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCampoEstadoIdToObjetivoPrecargado extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('objetivos_precargados', function (Blueprint $table) {
            // Agregar el campo estado_id
            $table->unsignedInteger('estado_id')->default(0)->after('id');
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
        Schema::table('objetivos_precargados', function (Blueprint $table) {
            // Eliminar el campo estado_id
            $table->dropColumn('estado_id');
            
            //
        });
    }
}
