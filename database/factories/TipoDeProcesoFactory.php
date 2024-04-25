<?php

namespace Database\Factories;

use App\Models\TipoDeProceso;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class TipoDeProcesoFactory extends Factory
{
    protected $model = TipoDeProceso::class;

    public function definition()
    {
        return [
			'name' => $this->faker->name,
			'estado' => $this->faker->name,
        ];
    }
}
