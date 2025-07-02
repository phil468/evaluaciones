<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateTipoDePuestosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tipo_de_puestos', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->unsignedBigInteger('nivel_jerarquico_id')->nullable();
            $table->boolean('estado')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('nivel_jerarquico_id')->references('id')->on('nivel_jerarquicos');
        });

        DB::table('tipo_de_puestos')->insert([
            ['name' => 'Gerencia Corporativa', 'nivel_jerarquico_id' => 1, 'estado' => true],
            ['name' => 'Gerencia de Línea', 'nivel_jerarquico_id' => 1, 'estado' => true],
            ['name' => 'Subgerencia', 'nivel_jerarquico_id' => 2, 'estado' => true],
            ['name' => 'Jefatura I', 'nivel_jerarquico_id' => 2, 'estado' => true],
            ['name' => 'Jefatura II/Coordinador/Gestor', 'nivel_jerarquico_id' => 3, 'estado' => true],
            ['name' => 'Analista', 'nivel_jerarquico_id' => 3, 'estado' => true],
            ['name' => 'Supervisor', 'nivel_jerarquico_id' => 3, 'estado' => true],
            ['name' => 'Asistente', 'nivel_jerarquico_id' => 4, 'estado' => true],
            ['name' => 'Auxiliar', 'nivel_jerarquico_id' => 4, 'estado' => true],
            ['name' => 'Técnico', 'nivel_jerarquico_id' => 4, 'estado' => true],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tipo_de_puestos');
    }
}