<?php
// Nueva migración para agregar campos a evaluador_has_evaluados
// filepath: database/migrations/xxxx_xx_xx_add_campos_to_evaluador_has_evaluados.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCamposToEvaluadorHasEvaluados extends Migration
{
    public function up()
    {
        Schema::table('evaluador_has_evaluados', function (Blueprint $table) {
            $table->unsignedBigInteger('campania_id')->nullable()->after('evaluacion_id');
            $table->unsignedBigInteger('grado_id')->nullable()->after('campania_id');
            $table->decimal('peso', 8, 4)->nullable()->after('grado_id');
            $table->decimal('peso_prorrateado', 8, 4)->nullable()->after('peso');
        });
    }

    public function down()
    {
        Schema::table('evaluador_has_evaluados', function (Blueprint $table) {
            $table->dropColumn(['campania_id', 'grado_id', 'peso', 'peso_prorrateado']);
        });
    }
}