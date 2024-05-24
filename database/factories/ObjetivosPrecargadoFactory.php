<?php

namespace Database\Factories;

use App\Models\ObjetivosPrecargado;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ObjetivosPrecargadoFactory extends Factory
{
    protected $model = ObjetivosPrecargado::class;

    public function definition()
    {
        return [
			'meta' => $this->faker->name,
			'grupal' => $this->faker->name,
			'porcentaje_de_participacion' => $this->faker->name,
			'evidencias' => $this->faker->name,
			'resultado_anterior_o_esperado' => $this->faker->name,
			'tipo_objetivo_id' => $this->faker->name,
			'minimo' => $this->faker->name,
			'maximo' => $this->faker->name,
			'valor' => $this->faker->name,
			'porcentaje_de_logro_STI' => $this->faker->name,
			'peso_ponderado' => $this->faker->name,
			'evaluacion_id' => $this->faker->name,
        ];
    }
}
