<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;


class AddPermissionsForEvaluaciones extends Migration
{
    /**
     * Lista de permisos a insertar
     */
    protected $permissions = [
        // Calibración
        'ver-calibracion',
        'editar-calibracion',
        
        // Escala de medición
        'ver-escala-medicion',
        'crear-escala-medicion',
        'editar-escala-medicion',
        'borrar-escala-medicion',
        
        // Tipo de medición
        'ver-tipo-medicion',
        'crear-tipo-medicion',
        'editar-tipo-medicion',
        'borrar-tipo-medicion',
        
        // Tipo de competencia
        'ver-tipo-competencia',
        'crear-tipo-competencia',
        'editar-tipo-competencia',
        'borrar-tipo-competencia',
        
        // Competencias
        'ver-competencias',
        'crear-competencias',
        'editar-competencias',
        'borrar-competencias',
        
        // Configuración campaña-competencia
        'ver-configuracion-campania-competencia',
        'crear-configuracion-campania-competencia',
        'editar-configuracion-campania-competencia',
        'borrar-configuracion-campania-competencia',
        
        // Nivel jerárquico
        'ver-nivel-jerarquico',
        'crear-nivel-jerarquico',
        'editar-nivel-jerarquico',
        'borrar-nivel-jerarquico',
        
        // Grado
        'ver-grado',
        'crear-grado',
        'editar-grado',
        'borrar-grado',
        
        // Dominio
        'ver-dominio',
        'crear-dominio',
        'editar-dominio',
        'borrar-dominio',
        
        // Tipo de puesto
        'ver-tipo-de-puesto',
        'crear-tipo-de-puesto',
        'editar-tipo-de-puesto',
        'borrar-tipo-de-puesto',
        
        // Tipo de puesto has nivel jerárquico
        'ver-tipo-de-puesto-has-nivel-jerarquico',
        'crear-tipo-de-puesto-has-nivel-jerarquico',
        'editar-tipo-de-puesto-has-nivel-jerarquico',
        'borrar-tipo-de-puesto-has-nivel-jerarquico',
        
        // Dominio has pregunta
        'ver-dominio-has-pregunta',
        'crear-dominio-has-pregunta',
        'editar-dominio-has-pregunta',
        'borrar-dominio-has-pregunta',
        
        // Campaña
        'ver-campania',
        'crear-campania',
        'editar-campania',
        'borrar-campania',
        'restaurar-campania',
    ];

    /**
     * Run the migrations.
     *
     * @return void
     */

    public function up()
    {
        $now = now();
        
        foreach ($this->permissions as $permission) {
            DB::table('permissions')->insert([
                'name' => $permission,
                'guard_name' => 'web',
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        foreach ($this->permissions as $permission) {
            DB::table('permissions')
                ->where('name', $permission)
                ->where('guard_name', 'web')
                ->delete();
        }
    }
}
