<?php

namespace Database\Factories;

use App\Models\AsignacionTipo;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class AsignacionTipoFactory extends Factory
{
    protected $model = AsignacionTipo::class;

    public function definition()
    {
        return [
			'name' => $this->faker->name,
			'estado' => $this->faker->name,
        ];
    }
}
