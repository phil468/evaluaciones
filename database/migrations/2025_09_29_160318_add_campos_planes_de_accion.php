<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCamposPlanesDeAccion extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // agregar campos a la tabla planes_de_accion
        Schema::table('planes_de_accion', function (Blueprint $t) {
            $t->decimal('objetivo',10,2)->nullable();
            $t->decimal('alcanzado',10,2)->nullable();
            $t->decimal('porcentaje_cumplimiento',5,2)->nullable(); // calculado y persistido
            $t->string('estado_cumplimiento',40)->nullable();       // No cumplimiento / Bajo / Medio / Esperado
            $t->enum('tipo_objetivo',['numerico','porcentual'])->default('numerico');
            $t->enum('estado_aprobacion',['borrador','pendiente','validado','no_validado'])
              ->default(null)->nullable()->index();
            $t->text('observacion_validacion')->nullable();
            $t->timestamp('enviado_para_validacion_at')->nullable();
            $t->timestamp('aprobado_revisado_at')->nullable();
            $t->timestamp('ultima_notificacion_aprobacion_at')->nullable();
        });
        //
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('planes_de_accion', function (Blueprint $t) {
            $t->dropColumn([
                'objetivo','alcanzado','porcentaje_cumplimiento','estado_cumplimiento',
                'tipo_objetivo','estado_aprobacion','observacion_validacion',
                'enviado_para_validacion_at','aprobado_revisado_at','ultima_notificacion_aprobacion_at'
            ]);
        });
    }
}
