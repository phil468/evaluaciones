<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreatePesosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pesos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tipo_relacion_jerarquica_id')->nullable();
            $table->float('peso')->default(0);
            $table->unsignedBigInteger('grado_id')->nullable();
            $table->unsignedBigInteger('campania_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            // Relaciones
            $table->foreign('tipo_relacion_jerarquica_id')
                ->references('id')
                ->on('tipo_relacion_jerarquicas')
                ->onDelete('set null');
                
            $table->foreign('grado_id')
                ->references('id')
                ->on('grados')
                ->onDelete('set null');
                
            $table->foreign('campania_id')
                ->references('id')
                ->on('campanias')
                ->onDelete('set null');
        });

        // Insertamos los datos iniciales
        DB::table('pesos')->insert([
            ['tipo_relacion_jerarquica_id' => 1, 'peso' => 1, 'grado_id' => 1, 'campania_id' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['tipo_relacion_jerarquica_id' => 1, 'peso' => 0.8, 'grado_id' => 2, 'campania_id' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['tipo_relacion_jerarquica_id' => 2, 'peso' => 0.2, 'grado_id' => 2, 'campania_id' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['tipo_relacion_jerarquica_id' => 1, 'peso' => 0.6, 'grado_id' => 3, 'campania_id' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['tipo_relacion_jerarquica_id' => 2, 'peso' => 0.2, 'grado_id' => 3, 'campania_id' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['tipo_relacion_jerarquica_id' => 3, 'peso' => 0.2, 'grado_id' => 3, 'campania_id' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['tipo_relacion_jerarquica_id' => 4, 'peso' => 0, 'grado_id' => 1, 'campania_id' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['tipo_relacion_jerarquica_id' => 4, 'peso' => 0, 'grado_id' => 2, 'campania_id' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['tipo_relacion_jerarquica_id' => 4, 'peso' => 0, 'grado_id' => 3, 'campania_id' => 2, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pesos');
    }
}