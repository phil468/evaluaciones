<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AddPesoPermissions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $permissions = [
            'ver-peso',
            'crear-peso',
            'editar-peso',
            'borrar-peso',
        ];

        // Crear permisos con DB directamente
        $now = now();
        $createdPermissionIds = [];
        
        foreach ($permissions as $permission) {
            // Insertar permisos ignorando duplicados
            DB::table('permissions')->insertOrIgnore([
                'name' => $permission,
                'guard_name' => 'web',
                'created_at' => $now,
                'updated_at' => $now,
            ]);
            
            // Obtener el ID del permiso recién insertado o existente
            $permId = DB::table('permissions')
                      ->where('name', $permission)
                      ->where('guard_name', 'web')
                      ->value('id');
                      
            if ($permId) {
                $createdPermissionIds[] = $permId;
            }
        }
        
        // Obtener ID del rol administrador
        $adminRoleId = DB::table('roles')
            ->where('name', 'Administrador')
            ->where('guard_name', 'web')
            ->value('id');
            
        // Asignar permisos al rol
        if ($adminRoleId) {
            foreach ($createdPermissionIds as $permissionId) {
                DB::table('role_has_permissions')->insertOrIgnore([
                    'permission_id' => $permissionId,
                    'role_id' => $adminRoleId,
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
        // Eliminar asignaciones y permisos
        $permissions = [
            'ver-peso',
            'crear-peso',
            'editar-peso',
            'borrar-peso',
        ];
        
        $permissionIds = DB::table('permissions')
            ->whereIn('name', $permissions)
            ->where('guard_name', 'web')
            ->pluck('id');
            
        // Eliminar relaciones de role_has_permissions
        DB::table('role_has_permissions')
            ->whereIn('permission_id', $permissionIds)
            ->delete();
            
        // Eliminar permisos
        DB::table('permissions')
            ->whereIn('id', $permissionIds)
            ->delete();
    }
}