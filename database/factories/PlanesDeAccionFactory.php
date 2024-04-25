<?php

namespace Database\Factories;

use App\Models\PlanesDeAccion;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PlanesDeAccionFactory extends Factory
{
    protected $model = PlanesDeAccion::class;

    public function definition()
    {
        return [
			'encargado_id' => $this->faker->name,
			'empleado_id' => $this->faker->name,
			'competencia_id' => $this->faker->name,
			'tipo_de_proceso_id' => $this->faker->name,
			'proceso_id' => $this->faker->name,
			'fecha_de_revision' => $this->faker->name,
			'estado_id' => $this->faker->name,
			'gerencia_id' => $this->faker->name,
			'area_id' => $this->faker->name,
			'avance' => $this->faker->name,
			'name' => $this->faker->name,
        ];
    }
}
