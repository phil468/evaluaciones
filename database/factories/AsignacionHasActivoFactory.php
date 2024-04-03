<?php

namespace Database\Factories;

use App\Models\AsignacionHasActivo;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class AsignacionHasActivoFactory extends Factory
{
    protected $model = AsignacionHasActivo::class;

    public function definition()
    {
        return [
			'activo_id' => $this->faker->name,
			'asignacion_id' => $this->faker->name,
			'accesorios_entregados' => $this->faker->name,
			'accesorios_devueltos' => $this->faker->name,
			'performance_id' => $this->faker->name,
			'vigencia_id' => $this->faker->name,
			'fecha_de_vigencia' => $this->faker->name,
			'devuelto' => $this->faker->name,
			'fecha_de_devolucion' => $this->faker->name,
			'observaciones' => $this->faker->name,
        ];
    }
}
