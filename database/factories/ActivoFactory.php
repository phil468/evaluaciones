<?php

namespace Database\Factories;

use App\Models\Activo;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ActivoFactory extends Factory
{
    protected $model = Activo::class;

    public function definition()
    {
        return [
			'estado' => $this->faker->name,
			'activo_tipo_id' => $this->faker->name,
			'brand_id' => $this->faker->name,
			'modelo_id' => $this->faker->name,
			'serial_number' => $this->faker->name,
			'patrimonial_code' => $this->faker->name,
			'status_id' => $this->faker->name,
			'performance_id' => $this->faker->name,
			'IMEI1' => $this->faker->name,
			'IMEI2' => $this->faker->name,
			'orden_compra' => $this->faker->name,
			'fecha_compra' => $this->faker->name,
			'year' => $this->faker->name,
			'personal_id' => $this->faker->name,
			'fecha_asignacion' => $this->faker->name,
			'vigencia_id' => $this->faker->name,
			'baja_motivo_id' => $this->faker->name,
			'created_by' => $this->faker->name,
			'updated_by' => $this->faker->name,
			'deleted_by' => $this->faker->name,
			'observations' => $this->faker->name,
        ];
    }
}
