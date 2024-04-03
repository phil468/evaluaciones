<?php

namespace Database\Factories;

use App\Models\CamposTipoActivo;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CamposTipoActivoFactory extends Factory
{
    protected $model = CamposTipoActivo::class;

    public function definition()
    {
        return [
			'name' => $this->faker->name,
			'estado' => $this->faker->name,
        ];
    }
}
