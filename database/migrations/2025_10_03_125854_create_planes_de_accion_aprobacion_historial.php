<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlanesDeAccionAprobacionHistorial extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('planes_de_accion_aprobacion_historial', function(Blueprint $t){
            $t->id();
            $t->foreignId('planes_de_accion_id');
            $t->foreignId('user_id');
            $t->enum('estado_anterior',['borrador','pendiente','validado','no_validado'])->nullable();
            $t->enum('estado_nuevo',['borrador','pendiente','validado','no_validado']);
            $t->text('observacion')->nullable();
            $t->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('planes_de_accion_aprobacion_historial');
    }
}
