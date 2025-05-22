<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPesoAndCampaniaIdToRespuestasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('respuestas', function (Blueprint $table) {
            $table->float('peso')->default(1)->nullable(false);
            $table->unsignedBigInteger('campania_id')->default(1)->nullable(false);
        });
    }

    public function down()
    {
        Schema::table('respuestas', function (Blueprint $table) {
            $table->dropColumn('peso');
            $table->dropColumn('campania_id');
        });
    }
}
