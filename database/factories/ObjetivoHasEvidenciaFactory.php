<?php

namespace Database\Factories;

use App\Models\ObjetivoHasEvidencia;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ObjetivoHasEvidenciaFactory extends Factory
{
    protected $model = ObjetivoHasEvidencia::class;

    public function definition()
    {
        return [
			'objetivo_id' => $this->faker->name,
			'name' => $this->faker->name,
			'estado' => $this->faker->name,
        ];
    }
}
