<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCampaniaHasEvaluadosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('campania_has_evaluados', function (Blueprint $table) {
            $table->id();
            // Relaciones principales
            $table->unsignedInteger('personal_id')->comment('Relación con modelo personal');
            $table->unsignedBigInteger('campania_id')->comment('Relación con modelo campaña');
            $table->integer('area_id')->nullable()->comment('Relación con modelo area');
            $table->unsignedInteger('subgerencia_id')->nullable();
            $table->unsignedInteger('gerencia_id')->nullable();
            $table->integer('puesto_id')->nullable()->comment('Relación con modelo cargos');
            $table->unsignedBigInteger('tipo_de_puesto_campania_id')->nullable()->comment('Relación con modelo TipoDePuestoHasNivelJerarquico');
            $table->unsignedBigInteger('dominio_id')->nullable()->comment('Relación con modelo Dominio');
            
            // Campos de habilitación para evaluaciones
            $table->boolean('habilitado_para_evaluacion_de_competencias')->default(false);
            $table->date('fecha_baja_de_evaluacion_de_competencias')->nullable();
            $table->string('motivo_baja_de_evaluacion_de_competencias')->nullable();
            
            $table->boolean('habilitado_para_evaluacion_por_objetivos')->default(false);
            $table->date('fecha_baja_de_evaluacion_por_objetivos')->nullable();
            $table->string('motivo_baja_de_evaluacion_por_objetivos')->nullable();
            
            // Estados y campos adicionales
            $table->boolean('cesado')->default(false);
            $table->boolean('estado')->default(true);
            $table->unsignedInteger('superior_personal_id')->nullable()->comment('Relación con modelo personal, obviamente no puede ser el mismo personal_id');
            
            // Claves foráneas
            $table->foreign('personal_id')->references('id')->on('personal');
            $table->foreign('campania_id')->references('id')->on('campanias');
            $table->foreign('area_id')->references('id')->on('areas')->onDelete('set null');
            $table->foreign('puesto_id')->references('id')->on('cargos')->onDelete('set null');
            $table->foreign('tipo_de_puesto_campania_id')->references('id')->on('tipo_de_puesto_has_nivel_jerarquicos')->onDelete('set null');
            $table->foreign('dominio_id')->references('id')->on('dominios')->onDelete('set null');
            $table->foreign('superior_personal_id')->references('id')->on('personal')->onDelete('set null');
            
            // Índices
            $table->unique(['personal_id', 'campania_id']);
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('campania_has_evaluados');
    }
}
