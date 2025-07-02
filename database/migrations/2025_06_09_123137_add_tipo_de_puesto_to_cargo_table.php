<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTipoDePuestoToCargoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cargos', function (Blueprint $table) {
            // Add new column 'tipo_de_puesto' to the 'cargos' table
            $table->unsignedBigInteger('tipo_de_puesto_id')->nullable()->after('name')
                ->comment('ID del tipo de puesto asociado al cargo');
            // Add foreign key constraint to 'tipo_de_puesto_id' referencing 'id' in 'tipo_de_puestos' table
            $table->foreign('tipo_de_puesto_id')
                ->references('id')->on('tipo_de_puestos')
                ->onDelete('set null')
                ->onUpdate('cascade')
                ->comment('Foreign key constraint linking tipo_de_puesto_id to tipo_de_puestos table');
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
        Schema::table('cargos', function (Blueprint $table) {
            // Drop foreign key constraint and column 'tipo_de_puesto_id' from the 'cargos' table
            $table->dropForeign(['tipo_de_puesto_id']);
            $table->dropColumn('tipo_de_puesto_id');
            // Note: The column is dropped, so no need to specify the comment here
            
            //
        });
    }
}
