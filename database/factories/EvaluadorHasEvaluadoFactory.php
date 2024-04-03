<?php

namespace Database\Factories;

use App\Models\EvaluadorHasEvaluado;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class EvaluadorHasEvaluadoFactory extends Factory
{
    protected $model = EvaluadorHasEvaluado::class;

    public function definition()
    {
        return [
			'evaluador_id' => $this->faker->name,
			'evaluado_id' => $this->faker->name,
			'evaluacion' => $this->faker->name,
        ];
    }
}
