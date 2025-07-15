<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateObjetivoPrecargadoHasEvidencias extends Migration
{    
    public function up()
    {
        Schema::create('objetivo_precargado_has_evidencias', function (Blueprint $table) {
            //'objetivo_id','ruta','name','estado'
            $table->id()->comment('ID de la evidencia asociada al objetivo precargado');
            $table->integer('objetivo_precargado_id')->default(0)->comment('ID del objetivo precargado');
            $table->string('ruta')->nullable()->comment('Ruta del archivo de evidencia');
            $table->string('name')->nullable()->comment('Nombre del archivo de evidencia');
            $table->boolean('estado')->default(true)->comment('Estado de la evidencia, true para activo, false para inactivo');
            // Clave foránea para relacionar con el objetivo precargado
            $table->foreign('objetivo_precargado_id', 'fk_obj_prec_evidencias')
                ->references('id')
                ->on('objetivos_precargados')
                ->onDelete('cascade') // Eliminar evidencias si se elimina el objetivo precargado
                ->comment('Clave foránea que referencia al objetivo precargado');
            // Agregar timestamps para seguimiento de creación y actualización
            $table->timestamps();
            $table->softDeletes()->comment('Campo para manejar eliminaciones suaves (soft deletes)');
            //
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('objetivo_precargado_has_evidencias', function (Blueprint $table) {
            // Usar el mismo nombre corto al eliminar la restricción
            $table->dropForeign('fk_obj_prec_evidencias');
            // Eliminar los campos añadidos
            $table->dropColumn('objetivo_precargado_id');
            $table->dropColumn('ruta');
            $table->dropColumn('name');
            $table->dropColumn('estado');
            // Eliminar timestamps y softDeletes
            $table->dropTimestamps();
            $table->dropSoftDeletes();
            
            // Eliminar la tabla si es necesario
            Schema::dropIfExists('objetivo_precargado_has_evidencias');
            //
        });
    }
}
