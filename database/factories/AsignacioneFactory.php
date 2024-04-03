<?php

namespace Database\Factories;

use App\Models\Asignacione;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class AsignacioneFactory extends Factory
{
    protected $model = Asignacione::class;

    public function definition()
    {
        return [
			'personal_id' => $this->faker->name,
			'empresa_id' => $this->faker->name,
			'gerencia_id' => $this->faker->name,
			'sede_id' => $this->faker->name,
			'area_id' => $this->faker->name,
			'cargo_id' => $this->faker->name,
			'fecha' => $this->faker->name,
			'responsable_id' => $this->faker->name,
			'responsable_area_id' => $this->faker->name,
			'responsable_cargo_id' => $this->faker->name,
			'created_by' => $this->faker->name,
			'updated_by' => $this->faker->name,
			'deleted_by' => $this->faker->name,
			'pdf' => $this->faker->name,
        ];
    }
}
