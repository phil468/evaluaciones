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
                'name' => 'No Domina',
                'rango_menor' => 'IGUAL',
                'rango_mayor' => 'IGUAL',
                'color' => '#7f0000',
                'interpretacion' => 'No demuestra el comportamiento, ni siquiera cuando la situación lo exige. ',
                'recomendacion' => 'Intervención inmediata. Plan de mejora intensivo.',
                'estado' => true,
            ],
            [
                'valor_menor' => 1,
                'valor_mayor' => 3,
                'name' => 'BAJO',
                'rango_menor' => 'MAYOR',
                'rango_mayor' => 'MENOR',
                'color' => '#FF0000',
                'interpretacion' => 'Demuestra el comportamiento de forma muy limitada.',
                'recomendacion' => 'Requiere plan de mejora. Seguimiento continuo.',
                'estado' => true,
            ],
            [
                'valor_menor' => 4,
                'valor_mayor' => 6,
                'name' => 'MEDIO',
                'rango_menor' => 'MAYOR IGUAL',
                'rango_mayor' => 'MENOR',
                'color' => '#FFC000',
                'interpretacion' => 'Muestra el comportamiento en situaciones simples, con oportunidades de mejora.',
                'recomendacion' => 'Desarrollo puntual. Capacitación o coaching sugerido.',
                'estado' => true,
            ],
            [
                'valor_menor' => 7,
                'valor_mayor' => 8,
                'name' => 'ESPERADO',
                'rango_menor' => 'MAYOR IGUAL',
                'rango_mayor' => 'MENOR',
                'color' => '#92D050',
                'interpretacion' => 'Cumple con lo esperado en situaciones diversas.',
                'recomendacion' => 'Mantener desempeño. Refuerzo positivo.',
                'estado' => true,
            ],
            [
                'valor_menor' => 9,
                'valor_mayor' => 10,
                'name' => 'SUPERIOR',
                'rango_menor' => 'MAYOR IGUAL',
                'rango_mayor' => 'IGUAL',
                'color' => '#00B050',
                'interpretacion' => 'Supera lo esperado, incluso en situaciones complejas.',
                'recomendacion' => 'Considerar para reconocimiento, mentoría o promoción.',
                'estado' => true,
            ],
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('escala_mediciones');
    }
}