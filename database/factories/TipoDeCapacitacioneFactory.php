<?php

namespace Database\Factories;

use App\Models\TipoDeCapacitacione;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class TipoDeCapacitacioneFactory extends Factory
{
    protected $model = TipoDeCapacitacione::class;

    public function definition()
    {
        return [
			'name' => $this->faker->name,
			'estado' => $this->faker->name,
        ];
    }
}
