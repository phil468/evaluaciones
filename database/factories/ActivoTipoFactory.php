<?php

namespace Database\Factories;

use App\Models\ActivoTipo;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ActivoTipoFactory extends Factory
{
    protected $model = ActivoTipo::class;

    public function definition()
    {
        return [
			'name' => $this->faker->name,
			'estado' => $this->faker->name,
        ];
    }
}
