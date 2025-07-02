<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateActualizacionesPersonalTable extends Migration
{
    public function up()
    {
        Schema::create('actualizaciones_personal', function (Blueprint $table) {
            $table->id();
            $table->string('tipo'); // 'general', 'individual'
            $table->json('detalles');
            $table->unsignedBigInteger('ejecutado_por')->nullable();
            $table->boolean('ejecutado_por_sistema')->default(false);
            $table->timestamps();
            
            $table->foreign('ejecutado_por')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('actualizaciones_personal');
    }
}