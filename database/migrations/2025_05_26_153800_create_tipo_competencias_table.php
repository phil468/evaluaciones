<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateTipoCompetenciasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tipo_competencias', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('medicion_id');
            $table->boolean('estado')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('medicion_id')->references('id')->on('tipo_mediciones');
        });

        DB::table('tipo_competencias')->insert([
            [
                'name' => 'Valores',
                'medicion_id' => 1,
                'estado' => true,
            ],
            [
                'name' => 'Competencias cardinales',
                'medicion_id' => 1,
                'estado' => true,
            ],
            [
                'name' => 'Competencias de liderazgo',
                'medicion_id' => 2,
                'estado' => true,
            ],
            [
                'name' => 'Competencias de gestión',
                'medicion_id' => 2,
                'estado' => true,
            ],
            [
                'name' => 'Competencias de soporte',
                'medicion_id' => 2,
                'estado' => true,
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tipo_competencias');
    }
}