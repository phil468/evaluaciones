<?php

namespace Database\Factories;

use App\Models\Sesione;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class SesioneFactory extends Factory
{
    protected $model = Sesione::class;

    public function definition()
    {
        return [
			'capacitacion_id' => $this->faker->name,
			'numero_de_sesion' => $this->faker->name,
			'fecha' => $this->faker->name,
			'hora_inicio' => $this->faker->name,
			'hora_fin' => $this->faker->name,
        ];
    }
}
