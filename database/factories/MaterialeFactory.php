<?php

namespace Database\Factories;

use App\Models\Materiale;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class MaterialeFactory extends Factory
{
    protected $model = Materiale::class;

    public function definition()
    {
        return [
			'tipo_material_id' => $this->faker->name,
			'codigo' => $this->faker->name,
			'descripcion' => $this->faker->name,
        ];
    }
}
