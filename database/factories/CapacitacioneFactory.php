<?php

namespace Database\Factories;

use App\Models\Capacitacione;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CapacitacioneFactory extends Factory
{
    protected $model = Capacitacione::class;

    public function definition()
    {
        return [
			'empresa_id' => $this->faker->name,
			'capacitaciones_tipo_id' => $this->faker->name,
			'tema_id' => $this->faker->name,
			'sede_id' => $this->faker->name,
			'fecha_capacitacion' => $this->faker->name,
			'hora_inicio' => $this->faker->name,
			'hora_fin' => $this->faker->name,
			'expositor_id' => $this->faker->name,
			'cargo_expositor_id' => $this->faker->name,
			'registrador_id' => $this->faker->name,
			'cargo_registrador_id' => $this->faker->name,
			'fecha_registro' => $this->faker->name,
			'activo' => $this->faker->name,
			'estado' => $this->faker->name,
        ];
    }
}
