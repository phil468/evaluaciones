<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTipoDePuestoToPersonalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('personal', function (Blueprint $table) {
            // Add new column 'tipo_de_puesto' to the 'personal' table
            $table->bigInteger('tipo_de_puesto_id')
                ->nullable()
                ->after('cargo_id')
                ->comment('ID del tipo de puesto asociado al personal');
            // Add foreign key constraint for 'tipo_de_puesto_id'
            $table->foreign('tipo_de_puesto_id')
                ->references('id')
                ->on('tipo_de_puestos')
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
        Schema::table('personal', function (Blueprint $table) {
            // Drop foreign key constraint for 'tipo_de_puesto_id'
            $table->dropForeign(['tipo_de_puesto_id']);
            // Drop the 'tipo_de_puesto_id' column from the 'personal' table
            $table->dropColumn('tipo_de_puesto_id');
            
            //
        });
    }
}
