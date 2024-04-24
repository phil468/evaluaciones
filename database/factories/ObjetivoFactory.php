<?php

namespace Database\Factories;

use App\Models\Objetivo;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ObjetivoFactory extends Factory
{
    protected $model = Objetivo::class;

    public function definition()
    {
        return [
			'resultado' => $this->faker->name,
			'evaluado_id' => $this->faker->name,
			'evaluador_id' => $this->faker->name,
			'tipo_objetivo_id' => $this->faker->name,
			'descripcion' => $this->faker->name,
			'evidencia' => $this->faker->name,
        ];
    }
}
