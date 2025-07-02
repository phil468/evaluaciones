<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateTipoMedicionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tipo_mediciones', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->boolean('estado')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        DB::table('tipo_mediciones')->insert([
            [
                'name' => 'TRANSVERSAL',
                'estado' => true,
            ],
            [                
                'name' => 'ESPECIFICA',
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
        Schema::dropIfExists('tipo_mediciones');
    }
}
