<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
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
        // Crear permisos
        Permission::create(['name' => 'ver-tipo-relacion-jerarquica']);
        Permission::create(['name' => 'crear-tipo-relacion-jerarquica']);
        Permission::create(['name' => 'editar-tipo-relacion-jerarquica']);
        Permission::create(['name' => 'borrar-tipo-relacion-jerarquica']);

        // Asignar permisos al rol de administrador (ajusta esto según tus roles)
        $adminRole = Role::findByName('Administrador');
        if ($adminRole) {
            $adminRole->givePermissionTo([
                'ver-tipo-relacion-jerarquica',
                'crear-tipo-relacion-jerarquica',
                'editar-tipo-relacion-jerarquica',
                'borrar-tipo-relacion-jerarquica'
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
        // Eliminar permisos
        Permission::whereIn('name', [
            'ver-tipo-relacion-jerarquica',
            'crear-tipo-relacion-jerarquica',
            'editar-tipo-relacion-jerarquica',
            'borrar-tipo-relacion-jerarquica'
        ])->delete();
    }
}