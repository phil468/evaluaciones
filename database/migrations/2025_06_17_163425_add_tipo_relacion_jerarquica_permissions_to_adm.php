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
        // Crear permisos primero y forzar guardado
        $permissions = [
            'ver-tipo-relacion-jerarquica',
            'crear-tipo-relacion-jerarquica', 
            'editar-tipo-relacion-jerarquica',
            'borrar-tipo-relacion-jerarquica'
        ];
                
        // Asignar permisos en una nueva transacción
        $adminRole = Role::findByName('Administrador');
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
        // Actualizar tabla de migraciones para reflejar el rollback
        DB::table('migrations')
            ->where('migration', '2025_06_17_163425_add_tipo_relacion_jerarquica_permissions_to_adm')
            ->delete();
    }
}