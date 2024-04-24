<?php

namespace Database\Factories;

use App\Models\TipoDeEvaluacione;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class TipoDeEvaluacioneFactory extends Factory
{
    protected $model = TipoDeEvaluacione::class;

    public function definition()
    {
        return [
			'name' => $this->faker->name,
			'estado' => $this->faker->name,
        ];
    }
}
