<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateTipoRelacionJerarquicasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tipo_relacion_jerarquicas', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->nullable();
            $table->boolean('estado')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        // Insertar los datos iniciales
        DB::table('tipo_relacion_jerarquicas')->insert([
            ['name' => 'JEFE', 'estado' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'PAR', 'estado' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'SUBORDINADO', 'estado' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'UNO MISMO', 'estado' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tipo_relacion_jerarquicas');
    }
}