<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterCampaniaIdCampaniaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // cambiar el tipo de dato de campania_id en la tabla campanias
        Schema::table('campanias', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('campanias', function (Blueprint $table) {
            // revertir el tipo de dato a unsignedInteger
            $table->unsignedInteger('campania_id')->change();
        });
    }
}
