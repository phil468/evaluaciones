<?php

namespace Database\Factories;

use App\Models\EncargadosPlanesDeAccion;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class EncargadosPlanesDeAccionFactory extends Factory
{
    protected $model = EncargadosPlanesDeAccion::class;

    public function definition()
    {
        return [
			'encargado_id' => $this->faker->name,
			'empleado_id' => $this->faker->name,
			'evaluacion_id' => $this->faker->name,
			'realizado' => $this->faker->name,
        ];
    }
}
