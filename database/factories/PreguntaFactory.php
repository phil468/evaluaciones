<?php

namespace Database\Factories;

use App\Models\Pregunta;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PreguntaFactory extends Factory
{
    protected $model = Pregunta::class;

    public function definition()
    {
        return [
			'seccion_id' => $this->faker->name,
			'evaluacion_id' => $this->faker->name,
			'qid' => $this->faker->name,
			'pregunta' => $this->faker->name,
			'tipo' => $this->faker->name,
			'opciones' => $this->faker->name,
			'numero_orden' => $this->faker->name,
        ];
    }
}
