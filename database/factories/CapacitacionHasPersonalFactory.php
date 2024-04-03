<?php

namespace Database\Factories;

use App\Models\CapacitacionHasPersonal;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CapacitacionHasPersonalFactory extends Factory
{
    protected $model = CapacitacionHasPersonal::class;

    public function definition()
    {
        return [
			'personal_id' => $this->faker->name,
			'capacitacion_id' => $this->faker->name,
			'active' => $this->faker->name,
			'observaciones' => $this->faker->name,
			'empresa_id' => $this->faker->name,
			'gerencia_id' => $this->faker->name,
			'area_id' => $this->faker->name,
			'cargo_id' => $this->faker->name,
			'planilla_id' => $this->faker->name,
			'sede_id' => $this->faker->name,
			'tipo_de_trabajador_id' => $this->faker->name,
			'tipo_de_personal_id' => $this->faker->name,
        ];
    }
}
