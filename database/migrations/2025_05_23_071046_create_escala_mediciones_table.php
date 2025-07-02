<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateEscalaMedicionesTable extends Migration
{
    public function up()
    {
        Schema::create('escala_mediciones', function (Blueprint $table) {
            $table->id();
            $table->integer('valor_menor')->nullable();
            $table->integer('valor_mayor')->nullable();
            $table->string('name')->nullable();
            $table->string('rango_menor')->nullable();
            $table->string('rango_mayor')->nullable();
            $table->string('color')->nullable();
            $table->text('interpretacion')->nullable();
            $table->text('recomendacion')->nullable();
            $table->boolean('estado')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        DB::table('escala_mediciones')->insert([
            [
                'valor_menor' => 0,
                'valor_mayor' => 0,
                'name' => 'ND',
                'rango_menor' => 'IGUAL',
                'rango_mayor' => 'IGUAL',
                'color' => '#FF0000',
                'estado' => true,
            ],
            [
                'valor_menor' => 0,
                'valor_mayor' => 5,
                'name' => 'BAJO',
                'rango_menor' => 'MAYOR',
                'rango_mayor' => 'MENOR',
                'color' => '#FF0000',
                'estado' => true,
            ],
            [
                'valor_menor' => 5,
                'valor_mayor' => 8,
                'name' => 'MEDIO',
                'rango_menor' => 'MAYOR IGUAL',
                'rango_mayor' => 'MENOR',
                'color' => '#FFC000',
                'estado' => true,
            ],
            [
                'valor_menor' => 8,
                'valor_mayor' => 9,
                'name' => 'ESPERADO',
                'rango_menor' => 'MAYOR IGUAL',
                'rango_mayor' => 'MENOR',
                'color' => '#92D050',
                'estado' => true,
            ],
            [
                'valor_menor' => 9,
                'valor_mayor' => 10,
                'name' => 'SUPERIOR',
                'rango_menor' => 'MAYOR IGUAL',
                'rango_mayor' => 'IGUAL',
                'color' => '#00B050',
                'estado' => true,
            ],
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('escala_mediciones');
    }
}