<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateTipoAreasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tipo_areas', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->boolean('estado')->default(true);
            $table->timestamps();
        });

        //insertar
        DB::table('tipo_areas')->insert([
            ['name' => 'AREA', 'estado' => true],
            ['name' => 'SUBGERENCIA', 'estado' => true],
            ['name' => 'GERENCIA', 'estado' => true],
            ['name' => 'GERENCIA CORPORATIVA', 'estado' => true],
        ]);

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tipo_areas');
    }
}
