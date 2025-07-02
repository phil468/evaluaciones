<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateDominiosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dominios', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->unsignedBigInteger('grado_id')->nullable();
            $table->unsignedBigInteger('nivel_jerarquico_id')->nullable();
            $table->unsignedBigInteger('campania_id')->nullable();
            $table->boolean('estado')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('grado_id')->references('id')->on('grados');
            $table->foreign('nivel_jerarquico_id')->references('id')->on('nivel_jerarquicos');
            $table->foreign('campania_id')->references('id')->on('campanias');
        });

        DB::table('dominios')->insert([
            ['name' => 'A', 'grado_id' => 3, 'nivel_jerarquico_id' => 1, 'campania_id' => 2, 'estado' => true],
            ['name' => 'B', 'grado_id' => 3, 'nivel_jerarquico_id' => 2, 'campania_id' => 2, 'estado' => true],
            ['name' => 'C', 'grado_id' => 2, 'nivel_jerarquico_id' => 3, 'campania_id' => 2, 'estado' => true],
            ['name' => 'D', 'grado_id' => 1, 'nivel_jerarquico_id' => 4, 'campania_id' => 2, 'estado' => true],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dominios');
    }
}