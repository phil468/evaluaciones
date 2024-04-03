<?php

namespace Database\Factories;

use App\Models\DistribucionVariedad;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class DistribucionVariedadFactory extends Factory
{
    protected $model = DistribucionVariedad::class;

    public function definition()
    {
        return [
			'fecha_proceso' => $this->faker->name,
			'procedencia_id' => $this->faker->name,
			'fruto_color_id' => $this->faker->name,
			'fruto_variedad_id' => $this->faker->name,
			'peso_distribuir' => $this->faker->name,
			'porcentaje_variedad' => $this->faker->name,
			'productor_id' => $this->faker->name,
        ];
    }
}
