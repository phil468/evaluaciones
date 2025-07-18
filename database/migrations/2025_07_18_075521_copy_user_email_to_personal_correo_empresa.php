<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;

class CopyUserEmailToPersonalCorreoEmpresa extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Verificar que las tablas necesarias existan
        if (Schema::hasTable('users') && Schema::hasTable('personal')) {
            $this->sincronizarCorreos();
        }
    }

    /**
     * Sincroniza el email del usuario con el correo_empresa del personal
     */
    private function sincronizarCorreos()
    {
        try {
            // Asumiendo que existe una relación entre personal y users mediante un campo user_id en la tabla personal
            $personal = DB::table('personal')
                ->join('users', 'personal.id', '=', 'users.personal_id')
                ->whereNotNull('users.personal_id')
                ->whereNotNull('users.email')
                ->select('personal.id as personal_id', 'users.email')
                ->get();
            
            $count = 0;
            foreach ($personal as $persona) {
                DB::table('personal')
                    ->where('id', $persona->personal_id)
                    ->update(['correo_empresa' => $persona->email]);
                $count++;
            }
            
            // Usar Log de Laravel para registrar la operación
            Log::info("Migración: Se actualizaron $count registros de correo_empresa en personal");
            
            // También imprimir en consola para cuando se ejecuta manualmente
            if (app()->runningInConsole()) {
                echo "Se actualizaron $count registros de correo_empresa en personal\n";
            }
        } catch (\Exception $e) {
            Log::error("Error en migración CopyUserEmailToPersonalCorreoEmpresa: " . $e->getMessage());
            
            if (app()->runningInConsole()) {
                echo "Error: " . $e->getMessage() . "\n";
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
        // Esta migración no se puede revertir de manera automática
        // ya que no hay forma de saber qué valores tenían anteriormente
        Log::info("Migración CopyUserEmailToPersonalCorreoEmpresa: down() no tiene efecto");
    }
}