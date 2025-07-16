<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class InsertEvaluacionesData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Insertar evaluación de competencias 2025
        DB::table('evaluaciones')->insertOrIgnore([
            'id' => 5,
            'eid' => NULL,
            'title' => 'Evaluación por competencias 2025',
            'date' => '2025-07-15 14:40:51',
            'status' => 1,
            'created_at' => '2025-06-06 10:57:05',
            'updated_at' => '2025-07-15 14:40:51',
            'deleted_at' => NULL,
            'nombre_para_mostrar' => 'Evaluación por competencias 2025',
            'campania' => NULL,
            'mes' => NULL,
            'anio' => NULL,
            'fecha_inicio' => '2025-06-06 10:53:00',
            'fecha_fin' => '2025-07-15 16:53:00',
            'fecha_corte' => '2025-01-01',
            'identificador' => 'eval-comp-2025',
            'recordatorios' => NULL,
            'tipo_de_evaluacion_id' => 1,
            'minimo' => 0,
            'maximo' => 0,
            'fecha_inicio_primera_fase_matricula' => NULL,
            'fecha_fin_primera_fase_matricula' => NULL,
            'fecha_inicio_segunda_fase' => NULL,
            'fecha_fin_segunda_fase' => NULL,
            'fecha_para_mostrar_resultados' => NULL,
            'campania_id' => 2,
        ]);

        // Insertar evaluación por objetivos 2025
        DB::table('evaluaciones')->insertOrIgnore([
            'id' => 6,
            'eid' => NULL,
            'title' => 'Evaluación por objetivos 2025',
            'date' => '2025-07-10 08:26:08',
            'status' => 1,
            'created_at' => '2025-06-06 10:58:06',
            'updated_at' => '2025-07-03 08:10:06',
            'deleted_at' => NULL,
            'nombre_para_mostrar' => 'Evaluación por objetivos 2025',
            'campania' => NULL,
            'mes' => NULL,
            'anio' => NULL,
            'fecha_inicio' => '2025-06-06 10:57:00',
            'fecha_fin' => '2025-06-07 10:57:00',
            'fecha_corte' => '2025-06-06',
            'identificador' => 'eval-obj-2025',
            'recordatorios' => NULL,
            'tipo_de_evaluacion_id' => 2,
            'minimo' => 0.2,
            'maximo' => 0.8,
            'fecha_inicio_primera_fase_matricula' => '2025-06-06 10:57:00',
            'fecha_fin_primera_fase_matricula' => '2025-06-06 10:57:00',
            'fecha_inicio_segunda_fase' => '2025-06-07 10:57:00',
            'fecha_fin_segunda_fase' => '2025-06-07 10:57:00',
            'fecha_para_mostrar_resultados' => '2025-06-06 10:57:00',
            'campania_id' => 2,
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Eliminar las evaluaciones insertadas
        DB::table('evaluaciones')->whereIn('id', [5, 6])->delete();
    }
}