<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateTipoDePuestoHasNivelJerarquicosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tipo_de_puesto_has_nivel_jerarquicos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tipo_de_puesto_id');
            $table->unsignedBigInteger('nivel_jerarquico_id');
            $table->unsignedBigInteger('campania_id')->nullable();
            $table->boolean('estado')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('tipo_de_puesto_id')->references('id')->on('tipo_de_puestos');
            $table->foreign('nivel_jerarquico_id')->references('id')->on('nivel_jerarquicos');
            $table->foreign('campania_id')->references('id')->on('campanias');
        });

        DB::table('tipo_de_puesto_has_nivel_jerarquicos')->insert([
            ['tipo_de_puesto_id' => 1, 'nivel_jerarquico_id' => 1, 'campania_id' => 2],
            ['tipo_de_puesto_id' => 2, 'nivel_jerarquico_id' => 1, 'campania_id' => 2],
            ['tipo_de_puesto_id' => 3, 'nivel_jerarquico_id' => 2, 'campania_id' => 2],
            ['tipo_de_puesto_id' => 4, 'nivel_jerarquico_id' => 2, 'campania_id' => 2],
            ['tipo_de_puesto_id' => 5, 'nivel_jerarquico_id' => 3, 'campania_id' => 2],
            ['tipo_de_puesto_id' => 6, 'nivel_jerarquico_id' => 3, 'campania_id' => 2],
            ['tipo_de_puesto_id' => 7, 'nivel_jerarquico_id' => 3, 'campania_id' => 2],
            ['tipo_de_puesto_id' => 8, 'nivel_jerarquico_id' => 4, 'campania_id' => 2],
            ['tipo_de_puesto_id' => 9, 'nivel_jerarquico_id' => 4, 'campania_id' => 2],
            ['tipo_de_puesto_id' => 10, 'nivel_jerarquico_id' => 4, 'campania_id' => 2],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tipo_de_puesto_has_nivel_jerarquicos');
    }
}