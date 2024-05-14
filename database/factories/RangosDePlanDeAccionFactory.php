<?php

namespace Database\Factories;

use App\Models\RangosDePlanDeAccion;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class RangosDePlanDeAccionFactory extends Factory
{
    protected $model = RangosDePlanDeAccion::class;

    public function definition()
    {
        return [
			'name' => $this->faker->name,
			'color' => $this->faker->name,
			'estado' => $this->faker->name,
			'nombre_para_mostrar' => $this->faker->name,
			'descripción' => $this->faker->name,
			'rango_mayor' => $this->faker->name,
        ];
    }
}
