<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AddTipoRelacionJerarquicaPermissions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Crear permisos primero y forzar guardado
        $permissions = [
            'ver-tipo-relacion-jerarquica',
            'crear-tipo-relacion-jerarquica', 
            'editar-tipo-relacion-jerarquica',
            'borrar-tipo-relacion-jerarquica'
        ];
        
        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission, 'guard_name' => 'web']);
        }        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Eliminar permisos
        $permissions = [
            'ver-tipo-relacion-jerarquica',
            'crear-tipo-relacion-jerarquica', 
            'editar-tipo-relacion-jerarquica',
            'borrar-tipo-relacion-jerarquica'
        ];
        
        foreach ($permissions as $permission) {
            Permission::where('name', $permission)
                ->where('guard_name', 'web')
                ->delete();           
        }
    }
}