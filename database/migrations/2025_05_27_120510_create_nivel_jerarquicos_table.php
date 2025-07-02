<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateNivelJerarquicosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nivel_jerarquicos', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->boolean('estado')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        // Insertar datos iniciales
        DB::table('nivel_jerarquicos')->insert([
            ['name' => 'NIVEL I', 'estado' => true],
            ['name' => 'NIVEL II', 'estado' => true],
            ['name' => 'NIVEL III', 'estado' => true],
            ['name' => 'NIVEL IV', 'estado' => true],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('nivel_jerarquicos');
    }
}