<?php

namespace Database\Factories;

use App\Models\Respuesta;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class RespuestaFactory extends Factory
{
    protected $model = Respuesta::class;

    public function definition()
    {
        return [
			'pregunta_id' => $this->faker->name,
			'opcion_id' => $this->faker->name,
			'valor_numerico' => $this->faker->name,
			'valor_texto' => $this->faker->name,
			'evaluado_id' => $this->faker->name,
        ];
    }
}
