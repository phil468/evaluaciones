<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AlterAreasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('areas', function (Blueprint $table) {
            // // string mejor que enum para no atarnos al motor
            $table->integer('tipo_id')->default(1)->after('name');
            $table->unsignedInteger('area_superior_id')->nullable()->after('tipo_id');

            $table->index('tipo_id');
            $table->index('area_superior_id');

            // Evita duplicados por tipo+name
            // $table->unique(['tipo_id', 'name', 'idccosto_nisira', 'estado'], 'areas_unique_tipo_name');

            // $table->foreign('area_superior_id')
            //     ->references('id')->on('areas')
            //     ->onDelete('set null');
        });

        // Si ya existen registros, aseguremos un tipo por defecto
        DB::table('areas')->whereNull('tipo_id')->update(['tipo_id' => 1]);
        //colocar todas las areas es estado false
        DB::table('areas')->update(['estado' => false]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('areas', function (Blueprint $table) {
            $table->dropForeign(['area_superior_id']);
            $table->dropIndex(['area_superior_id']);
            $table->dropIndex(['tipo_id']);
            $table->dropUnique('areas_unique_tipo_name');
            $table->dropColumn(['tipo_id', 'area_superior_id']);
        });
    }
}