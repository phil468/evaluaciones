<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddReportaAToCargosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cargos', function (Blueprint $table) {
            $table->integer('reporta_a')->nullable()->comment('ID del cargo al que reporta')->after('estado');
            // Agregar la clave foránea para la relación con el modelo Cargo
            $table->foreign('reporta_a')->references('id')->on('cargos')->onDelete('set null');
        });
        
        // Sincronizar datos existentes
        if (Schema::hasTable('personal') && Schema::hasTable('cargos')) {
            $this->sincronizarDatos();
        }
                
    }
    
    /**
     * Sincroniza el campo reporta_a entre las tablas personal y cargos
     */
    private function sincronizarDatos()
    {
        // Necesitamos importar las clases aquí para evitar problemas de carga
        $personalModel = config('auth.providers.users.model');
        if (!class_exists($personalModel)) {
            return;
        }
        
        $personal = DB::table('personal')
            ->join('personal as superior', 'personal.reporta_a', '=', 'superior.id')
            ->whereNotNull('personal.reporta_a')
            ->whereNotNull('personal.cargo_id')
            ->whereNotNull('superior.cargo_id')
            ->select('personal.id', 'personal.cargo_id', 'superior.cargo_id as superior_cargo_id')
            ->get();
        
        $count = 0;
        foreach ($personal as $persona) {
            DB::table('cargos')
                ->where('id', $persona->cargo_id)
                ->update(['reporta_a' => $persona->superior_cargo_id]);
            $count++;
        }
        
        // No podemos usar $this->info en migraciones
        // DB::statement("INSERT INTO migration_logs (message) VALUES ('Se actualizaron $count registros de cargos')");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cargos', function (Blueprint $table) {
            $table->dropForeign(['reporta_a']);
            $table->dropColumn('reporta_a');
        });
    }
}