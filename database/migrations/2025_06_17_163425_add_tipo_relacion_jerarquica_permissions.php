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
        
        // Forzar commit de la transacción actual
        DB::commit();
        
        // Asignar permisos en una nueva transacción
        DB::beginTransaction();
        $adminRole = Role::findByName('Administrador', 'web');
        if ($adminRole) {
            $adminRole->givePermissionTo($permissions);
        }
        DB::commit();
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
        
        // Actualizar tabla de migraciones para reflejar el rollback
        DB::table('migrations')
            ->where('migration', '2025_06_17_163425_add_tipo_relacion_jerarquica_permissions')
            ->delete();
    }
}