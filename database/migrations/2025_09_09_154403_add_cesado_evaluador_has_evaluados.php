<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCesadoEvaluadorHasEvaluados extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('evaluador_has_evaluados', function (Blueprint $table) {
            if (!Schema::hasColumn('evaluador_has_evaluados', 'cesado')) {
                $table->boolean('cesado')->default(false)->after('realizado');
            }
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
            if (Schema::hasColumn('evaluador_has_evaluados', 'cesado')) {
                $table->dropColumn('cesado');
            }
            //
        });
    }
}
