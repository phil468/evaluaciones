<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateCampaniaHasCompetenciasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('campania_has_competencias', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('competencia_id');
            $table->unsignedBigInteger('campania_id');
            $table->unsignedBigInteger('relacionado_anterior_id')->nullable();
            $table->boolean('estado')->default(true);
            $table->unsignedBigInteger('tipo_competencia_id')->nullable();
            $table->unsignedBigInteger('tipo_medicion_id')->nullable();
            $table->string('color')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('competencia_id')->references('id')->on('secciones');
            $table->foreign('campania_id')->references('id')->on('campanias');
            $table->foreign('tipo_competencia_id')->references('id')->on('tipo_competencias');
            $table->foreign('tipo_medicion_id')->references('id')->on('tipo_mediciones');
        });        

        // Verificar si existen las campañas necesarias
        if (DB::table('campanias')->where('id', 1)->doesntExist()) {
            // Insertar campaña 1 si no existe
            DB::table('campanias')->insert([
                'id' => 1,
                'name' => '2024-2025',
                'descripcion' => 'Campaña de evaluación 2024',
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        if (DB::table('campanias')->where('id', 2)->doesntExist()) {
            // Insertar campaña 2 si no existe
            DB::table('campanias')->insert([
                'id' => 2,
                'name' => '2025-2026',
                'relacionado_anterior_id' => 1,
                'estado' => true,
                'es_campania_actual' => true,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }


        // Insertar datos iniciales (registros 1 al 15)
        DB::table('campania_has_competencias')->insert([
            ['competencia_id' => 1, 'campania_id' => 1, 'relacionado_anterior_id' => null, 'estado' => true, 'tipo_competencia_id' => 4, 'tipo_medicion_id' => 2, 'color' => '#3c4651'],
            ['competencia_id' => 2, 'campania_id' => 1, 'relacionado_anterior_id' => null, 'estado' => true, 'tipo_competencia_id' => 4, 'tipo_medicion_id' => 2, 'color' => '#00b050'],
            ['competencia_id' => 3, 'campania_id' => 1, 'relacionado_anterior_id' => null, 'estado' => true, 'tipo_competencia_id' => 3, 'tipo_medicion_id' => 2, 'color' => '#6ecbc9'],
            ['competencia_id' => 4, 'campania_id' => 1, 'relacionado_anterior_id' => null, 'estado' => true, 'tipo_competencia_id' => 2, 'tipo_medicion_id' => 1, 'color' => '#558ba5'],
            ['competencia_id' => 5, 'campania_id' => 1, 'relacionado_anterior_id' => null, 'estado' => true, 'tipo_competencia_id' => null, 'tipo_medicion_id' => null, 'color' => '#6ecbc9'],
            ['competencia_id' => 6, 'campania_id' => 1, 'relacionado_anterior_id' => null, 'estado' => true, 'tipo_competencia_id' => 5, 'tipo_medicion_id' => 2, 'color' => '#ffd863'],
            ['competencia_id' => 7, 'campania_id' => 1, 'relacionado_anterior_id' => null, 'estado' => true, 'tipo_competencia_id' => null, 'tipo_medicion_id' => null, 'color' => '#558ba5'],
            ['competencia_id' => 8, 'campania_id' => 1, 'relacionado_anterior_id' => null, 'estado' => true, 'tipo_competencia_id' => 2, 'tipo_medicion_id' => 1, 'color' => '#3c4651'],
            ['competencia_id' => 9, 'campania_id' => 1, 'relacionado_anterior_id' => null, 'estado' => true, 'tipo_competencia_id' => 5, 'tipo_medicion_id' => 2, 'color' => '#00b050'],
            ['competencia_id' => 10, 'campania_id' => 1, 'relacionado_anterior_id' => null, 'estado' => true, 'tipo_competencia_id' => 1, 'tipo_medicion_id' => 1, 'color' => '#6ecbc9'],
            ['competencia_id' => 11, 'campania_id' => 1, 'relacionado_anterior_id' => null, 'estado' => true, 'tipo_competencia_id' => null, 'tipo_medicion_id' => null, 'color' => '#ffd863'],
            ['competencia_id' => 12, 'campania_id' => 1, 'relacionado_anterior_id' => null, 'estado' => true, 'tipo_competencia_id' => 1, 'tipo_medicion_id' => 1, 'color' => '#558ba5'],
            ['competencia_id' => 13, 'campania_id' => 1, 'relacionado_anterior_id' => null, 'estado' => true, 'tipo_competencia_id' => 1, 'tipo_medicion_id' => 1, 'color' => '#3c4651'],
            ['competencia_id' => 14, 'campania_id' => 1, 'relacionado_anterior_id' => null, 'estado' => true, 'tipo_competencia_id' => 3, 'tipo_medicion_id' => 2, 'color' => '#ffd863'],
            ['competencia_id' => 15, 'campania_id' => 1, 'relacionado_anterior_id' => null, 'estado' => true, 'tipo_competencia_id' => 3, 'tipo_medicion_id' => 2, 'color' => '#3c4651'],
        ]);

        // Insertar datos iniciales (registros 16 al 38)
        DB::table('campania_has_competencias')->insert([
            ['competencia_id' => 16, 'campania_id' => 1, 'relacionado_anterior_id' => null, 'estado' => true, 'tipo_competencia_id' => 4, 'tipo_medicion_id' => 2, 'color' => '#00b050'],
            ['competencia_id' => 17, 'campania_id' => 1, 'relacionado_anterior_id' => null, 'estado' => true, 'tipo_competencia_id' => null, 'tipo_medicion_id' => null, 'color' => '#00b050'],
            ['competencia_id' => 18, 'campania_id' => 1, 'relacionado_anterior_id' => null, 'estado' => true, 'tipo_competencia_id' => 3, 'tipo_medicion_id' => 2, 'color' => '#6ecbc9'],
            ['competencia_id' => 1, 'campania_id' => 2, 'relacionado_anterior_id' => 1, 'estado' => true, 'tipo_competencia_id' => 4, 'tipo_medicion_id' => 2, 'color' => null],
            ['competencia_id' => 2, 'campania_id' => 2, 'relacionado_anterior_id' => 2, 'estado' => true, 'tipo_competencia_id' => 4, 'tipo_medicion_id' => 2, 'color' => null],
            ['competencia_id' => 29, 'campania_id' => 2, 'relacionado_anterior_id' => 3, 'estado' => true, 'tipo_competencia_id' => 3, 'tipo_medicion_id' => 2, 'color' => null],
            ['competencia_id' => 9, 'campania_id' => 2, 'relacionado_anterior_id' => 9, 'estado' => true, 'tipo_competencia_id' => 5, 'tipo_medicion_id' => 2, 'color' => null],
            ['competencia_id' => 10, 'campania_id' => 2, 'relacionado_anterior_id' => 10, 'estado' => true, 'tipo_competencia_id' => 1, 'tipo_medicion_id' => 1, 'color' => null],
            ['competencia_id' => 12, 'campania_id' => 2, 'relacionado_anterior_id' => 12, 'estado' => true, 'tipo_competencia_id' => 1, 'tipo_medicion_id' => 1, 'color' => null],
            ['competencia_id' => 13, 'campania_id' => 2, 'relacionado_anterior_id' => 13, 'estado' => true, 'tipo_competencia_id' => 1, 'tipo_medicion_id' => 1, 'color' => null],
            ['competencia_id' => 14, 'campania_id' => 2, 'relacionado_anterior_id' => 14, 'estado' => true, 'tipo_competencia_id' => 3, 'tipo_medicion_id' => 2, 'color' => null],
            ['competencia_id' => 15, 'campania_id' => 2, 'relacionado_anterior_id' => 15, 'estado' => true, 'tipo_competencia_id' => 3, 'tipo_medicion_id' => 2, 'color' => null],
            ['competencia_id' => 16, 'campania_id' => 2, 'relacionado_anterior_id' => 16, 'estado' => true, 'tipo_competencia_id' => 4, 'tipo_medicion_id' => 2, 'color' => null],
            ['competencia_id' => 19, 'campania_id' => 2, 'relacionado_anterior_id' => null, 'estado' => true, 'tipo_competencia_id' => 1, 'tipo_medicion_id' => 1, 'color' => null],
            ['competencia_id' => 20, 'campania_id' => 2, 'relacionado_anterior_id' => 4, 'estado' => true, 'tipo_competencia_id' => 2, 'tipo_medicion_id' => 1, 'color' => null],
            ['competencia_id' => 21, 'campania_id' => 2, 'relacionado_anterior_id' => null, 'estado' => true, 'tipo_competencia_id' => 2, 'tipo_medicion_id' => 1, 'color' => null],
            ['competencia_id' => 22, 'campania_id' => 2, 'relacionado_anterior_id' => 8, 'estado' => true, 'tipo_competencia_id' => 2, 'tipo_medicion_id' => 1, 'color' => null],
            ['competencia_id' => 23, 'campania_id' => 2, 'relacionado_anterior_id' => null, 'estado' => true, 'tipo_competencia_id' => 3, 'tipo_medicion_id' => 2, 'color' => null],
            ['competencia_id' => 24, 'campania_id' => 2, 'relacionado_anterior_id' => null, 'estado' => true, 'tipo_competencia_id' => 4, 'tipo_medicion_id' => 2, 'color' => null],
            ['competencia_id' => 25, 'campania_id' => 2, 'relacionado_anterior_id' => 6, 'estado' => true, 'tipo_competencia_id' => 5, 'tipo_medicion_id' => 2, 'color' => null],
            ['competencia_id' => 26, 'campania_id' => 2, 'relacionado_anterior_id' => null, 'estado' => true, 'tipo_competencia_id' => 2, 'tipo_medicion_id' => 1, 'color' => null],
            ['competencia_id' => 27, 'campania_id' => 2, 'relacionado_anterior_id' => null, 'estado' => true, 'tipo_competencia_id' => 3, 'tipo_medicion_id' => 2, 'color' => null],
            ['competencia_id' => 28, 'campania_id' => 2, 'relacionado_anterior_id' => 18, 'estado' => true, 'tipo_competencia_id' => 3, 'tipo_medicion_id' => 2, 'color' => null],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('campania_has_competencias');
    }
}