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
    
    // Crear permisos si no existen
    $now = now();
    foreach ($permissions as $permission) {
        DB::table('permissions')->insertOrIgnore([
            'name' => $permission,
            'guard_name' => 'web',
            'created_at' => $now,
            'updated_at' => $now
        ]);
    }
    
    // Obtener ID del rol administrador
    $adminRoleId = DB::table('roles')
        ->where('name', 'Administrador')
        ->where('guard_name', 'web')
        ->value('id');
    
    if ($adminRoleId) {
        // Obtener IDs de los permisos
        $permissionIds = DB::table('permissions')
            ->whereIn('name', $permissions)
            ->where('guard_name', 'web')
            ->pluck('id');
        
        // Asignar permisos al rol
        foreach ($permissionIds as $permissionId) {
            DB::table('role_has_permissions')->insertOrIgnore([
                'permission_id' => $permissionId,
                'role_id' => $adminRoleId
            ]);
        }
    }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        
    }
}