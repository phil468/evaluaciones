<?php

namespace Database\Factories;

use App\Models\EstadosDePlanDeAccion;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class EstadosDePlanDeAccionFactory extends Factory
{
    protected $model = EstadosDePlanDeAccion::class;

    public function definition()
    {
        return [
			'name' => $this->faker->name,
			'color' => $this->faker->name,
			'estado' => $this->faker->name,
        ];
    }
}
