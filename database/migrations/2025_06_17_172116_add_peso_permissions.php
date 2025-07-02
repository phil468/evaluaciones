<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
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
        // Crear permisos para Pesos
        $permissions = [
            'ver-peso',
            'crear-peso',
            'editar-peso',
            'borrar-peso',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Asignar permisos al rol de administrador
        $role = Role::findByName('Administrador');
        if ($role) {
            $role->givePermissionTo($permissions);
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
            'ver-peso',
            'crear-peso',
            'editar-peso',
            'borrar-peso',
        ];

        foreach ($permissions as $permission) {
            // Verificamos si el permiso existe antes de intentar eliminarlo
            if (Permission::where('name', $permission)->exists()) {
                Permission::where('name', $permission)->delete();
            }
        }
    }
}