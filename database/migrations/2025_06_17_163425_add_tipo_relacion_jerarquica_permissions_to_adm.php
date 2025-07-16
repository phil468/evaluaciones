<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AddTipoRelacionJerarquicaPermissionsToAdm extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $permissions = [
            'ver-tipo-relacion-jerarquica',
            'crear-tipo-relacion-jerarquica', 
            'editar-tipo-relacion-jerarquica',
            'borrar-tipo-relacion-jerarquica'
        ];
        
        // Primero verificar y crear los permisos si no existen
        foreach ($permissions as $permission) {
            // Verificar si el permiso ya existe
            if (!Permission::where('name', $permission)->where('guard_name', 'web')->exists()) {
                // Crear el permiso si no existe
                Permission::create(['name' => $permission, 'guard_name' => 'web']);
            }
        }
        
        // Forzar que se guarden los cambios en la base de datos
        DB::commit();
        DB::beginTransaction();
        
        // Ahora asignar los permisos al rol administrador
        $adminRole = Role::findByName('Administrador', 'web');
        if ($adminRole) {
            $adminRole->givePermissionTo($permissions);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Desasignar permisos del rol administrador
        $permissions = [
            'ver-tipo-relacion-jerarquica',
            'crear-tipo-relacion-jerarquica', 
            'editar-tipo-relacion-jerarquica',
            'borrar-tipo-relacion-jerarquica'
        ];
        
        $adminRole = Role::findByName('Administrador', 'web');
        if ($adminRole) {
            $adminRole->revokePermissionTo($permissions);
        }
    }
}